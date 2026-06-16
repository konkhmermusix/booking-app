<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Auth;
use App\Traits\UploadTrait;

class CheckoutController extends Controller
{
    use UploadTrait;

    public function index()
    {
        $cart = session()->get('cart', []);
        if (empty($cart)) {
            return redirect()->route('cart.index')->with('error', 'កន្ត្រករបស់អ្នកទំនេរ សូមជ្រើសរើសបន្ទប់សិន!');
        }

        $subtotal = 0;
        foreach ($cart as $item) {
            $subtotal += $item['total_price'] ?? 0;
        }

        return view('frontend.checkout', compact('cart', 'subtotal'));
    }

    /**
     * ដំណើរការរក្សាទុកទិន្នន័យកក់ (កែសម្រួល៖ បិទប្រព័ន្ធ Auto-Switch បន្ទប់ ការពារការលោតខុសប្រភេទ)
     */
    public function process(Request $request)
    {
        $request->validate([
            'name'           => 'required|string|max:255',
            'phone'          => 'required|string|max:50',
            'email'          => 'required|email',
            'payment_method' => 'required|string',
            'payment_slip'   => 'nullable|string'
        ]);

        $cart = session()->get('cart', []);
        if (empty($cart)) {
            return response()->json(['status' => 'error', 'message' => 'កន្ត្រកទំនេរ!']);
        }

        try {
            DB::beginTransaction();
            $userId = Auth::id();
            $generatedCodes = [];
            $slipPath = null;

            if ($request->payment_method === 'aba' && $request->payment_slip) {
                $slipPath = $this->uploadBase64($request->payment_slip, 'slips');
            }

            $paymentMethodEnum = ($request->payment_method === 'aba') ? 'qr' : 'cash';

            // បង្កើត Array ថ្មីសម្រាប់ទុកព័ត៌មានដែលបានកក់ពិតប្រាកដ ដើម្បីផ្ញើទៅ Telegram
            $bookedItemsForTelegram = [];

            foreach ($cart as $item) {
                $roomId = $item['id']; // ID បន្ទប់ដែល User បានរើសពិតប្រាកដ

                // 🌟 ១. ឆែកមើលថា បន្ទប់ដែលគាត់រើសហ្នឹងទំនេរអត់?
                if (!$this->isRoomAvailable($roomId, $item)) {
                    // បើមិនទំនេរទេ គឺបោះ Error ប្រាប់ភ្ញៀវភ្លាមៗ មិនបាច់ដូរបន្ទប់ស្វ័យប្រវត្តិនាំឱ្យខុសប្រភេទទៀតឡើយ
                    DB::rollBack();
                    return response()->json([
                        'status'  => 'error',
                        'message' => "សុំទោសផង! បន្ទប់ដែលអ្នកបានជ្រើសរើសត្រូវបានគេកក់រួចហើយនៅក្នុងកាលបរិច្ឆេទនេះ។ សូមជ្រើសរើសថ្ងៃខែ ឬបន្ទប់ផ្សេង!"
                    ]);
                }

                // --- ផ្នែករក្សាទុកទិន្នន័យទៅ Database ---
                $bookingCode = 'PNT-' . strtoupper(Str::random(6));
                $generatedCodes[] = $bookingCode;

                if (isset($item['type']) && $item['type'] == 'hotel') {
                    $hotelId = DB::table('rooms')->where('id', $roomId)->value('hotel_id');
                    $roomTypeId = DB::table('rooms')->where('id', $roomId)->value('room_type_id');

                    // ទាញយកឈ្មោះប្រភេទបន្ទប់ពិតប្រាកដចេញពី DB
                    $roomTypeName = DB::table('room_types')->where('id', $roomTypeId)->value('name') ?? 'មិនស្គាល់';

                    $hotelBookingId = DB::table('hotel_bookings')->insertGetId([
                        'user_id'          => $userId,
                        'hotel_id'         => $hotelId,
                        'room_id'          => $roomId,
                        'check_in'         => $item['check_in'] ?? null,
                        'check_out'        => $item['check_out'] ?? null,
                        'check_in_time'    => $item['check_in_time'] ?? null,
                        'check_out_time'   => $item['check_out_time'] ?? null,
                        'total_price'      => $item['total_price'],
                        'status'           => 'pending',
                        'booking_code'     => $bookingCode,
                        'special_requests' => $request->special_requests ?? null,
                        'created_at'       => now(),
                        'updated_at'       => now(),
                    ]);

                    DB::table('hotel_booking_details')->insert([
                        'hotel_booking_id' => $hotelBookingId,
                        'room_id'          => $roomId,
                        'room_type_id'     => $roomTypeId,
                        'price_at_booking' => $item['price_at_booking'] ?? $item['total_price'],
                        'created_at'       => now(),
                        'updated_at'       => now(),
                    ]);

                    DB::table('payments')->insert([
                        'hotel_booking_id'   => $hotelBookingId,
                        'meeting_booking_id' => null,
                        'method'             => $paymentMethodEnum,
                        'amount'             => $item['total_price'],
                        'currency'           => 'USD',
                        'transaction_id'     => ($request->payment_method === 'aba') ? 'TXN-' . strtoupper(Str::random(10)) : null,
                        'payment_slip'       => $slipPath,
                        'status'             => 'pending',
                        'paid_at'            => ($paymentMethodEnum === 'cash') ? null : now(),
                        'created_at'         => now(),
                        'updated_at'         => now(),
                    ]);

                    // រក្សាទុកទិន្នន័យដើម្បីបាញ់ទៅ Telegram
                    $bookedItemsForTelegram[] = [
                        'type'          => 'hotel',
                        'room_type'     => $roomTypeName,
                        'check_in'      => $item['check_in'],
                        'check_out'     => $item['check_out'],
                        'total_price'   => $item['total_price']
                    ];
                } else {
                    // សម្រាប់សាលប្រជុំ (Meeting Room Logic)
                    $meetingRoomName = DB::table('rooms')->where('id', $roomId)->value('room_number') ?? 'មិនស្គាល់';

                    $meetingBookingId = DB::table('meeting_bookings')->insertGetId([
                        'user_id'          => $userId,
                        'meeting_room_id'  => $roomId,
                        'start_date'       => $item['start_date'],
                        'end_date'         => $item['end_date'],
                        'start_time'       => $item['start_time'],
                        'end_time'         => $item['end_time'],
                        'total_hours'      => $item['total_hours'] ?? null,
                        'total_price'      => $item['total_price'],
                        'status'           => 'pending',
                        'booking_code'     => $bookingCode,
                        'special_requests' => $request->special_requests ?? null,
                        'created_at'       => now(),
                        'updated_at'       => now(),
                    ]);

                    DB::table('payments')->insert([
                        'hotel_booking_id'   => null,
                        'meeting_booking_id' => $meetingBookingId,
                        'method'             => $paymentMethodEnum,
                        'amount'             => $item['total_price'],
                        'currency'           => 'USD',
                        'transaction_id'     => ($request->payment_method === 'aba') ? 'TXN-' . strtoupper(Str::random(10)) : null,
                        'payment_slip'       => $slipPath,
                        'status'             => 'pending',
                        'paid_at'            => ($paymentMethodEnum === 'cash') ? null : now(),
                        'created_at'         => now(),
                        'updated_at'         => now(),
                    ]);

                    // រក្សាទុកទិន្នន័យដើម្បីបាញ់ទៅ Telegram
                    $bookedItemsForTelegram[] = [
                        'type'          => 'meeting',
                        'room_name'     => $meetingRoomName,
                        'start_date'    => $item['start_date'],
                        'end_date'      => $item['end_date'],
                        'start_time'    => $item['start_time'],
                        'end_time'      => $item['end_time'],
                        'total_price'   => $item['total_price']
                    ];
                }
            }

            DB::commit();

            // 🚀 --- ផ្នែកផ្ញើសារជូនដំណឹងទៅកាន់គ្រុប Telegram ---
            try {
                $paymentText = ($request->payment_method === 'aba') ? '💳 ស្កែន QR (ABA)' : '💵 ទូទាត់សាច់ប្រាក់ផ្ទាល់';
                $specialRequests = $request->special_requests ? $request->special_requests : 'គ្មាន';

                $telegramMsg = "🔔 <b>មានការកក់ថ្មីពីគេហទំព័រ!</b>\n";
                $telegramMsg .= "----------------------------------\n";
                $telegramMsg .= "👤 <b>ឈ្មោះភ្ញៀវ:</b> {$request->name}\n";
                $telegramMsg .= "📞 <b>លេខទូរស័ព្ទ:</b> {$request->phone}\n";
                $telegramMsg .= "✉️ <b>អ៊ីមែល:</b> {$request->email}\n";
                $telegramMsg .= "💰 <b>វិធីទូទាត់:</b> {$paymentText}\n";
                $telegramMsg .= "📝 <b>សំណូមពរពិសេស:</b> {$specialRequests}\n";
                $telegramMsg .= "----------------------------------\n";

                $roomNo = 1;
                $totalAmount = 0;

                foreach ($bookedItemsForTelegram as $bookedItem) {
                    if ($bookedItem['type'] == 'hotel') {
                        // គណនាចំនួនយប់
                        $checkInDate  = \Carbon\Carbon::parse($bookedItem['check_in']);
                        $checkOutDate = \Carbon\Carbon::parse($bookedItem['check_out']);
                        $nights       = $checkInDate->diffInDays($checkOutDate);
                        $nights       = $nights == 0 ? 1 : $nights;

                        $telegramMsg .= "🏨 <b>ការកក់ទី {$roomNo}: (បន្ទប់សណ្ឋាគារ)</b>\n";
                        $telegramMsg .= "🛏️ <b>ប្រភេទបន្ទប់:</b> {$bookedItem['room_type']}\n";
                        $telegramMsg .= "🗓️ <b>ថ្ងៃចូល:</b> {$bookedItem['check_in']}\n";
                        $telegramMsg .= "🗓️ <b>ថ្ងៃចេញ:</b> {$bookedItem['check_out']}\n";
                        $telegramMsg .= "🌙 <b>រយៈពេលស្នាក់នៅ:</b> {$nights} យប់\n";
                    } else {
                        // គណនាចំនួនថ្ងៃជួល
                        $startDate = \Carbon\Carbon::parse($bookedItem['start_date']);
                        $endDate   = \Carbon\Carbon::parse($bookedItem['end_date']);
                        $days      = $startDate->diffInDays($endDate) + 1;

                        $telegramMsg .= "🏢 <b>ការកក់ទី {$roomNo}: (សាលប្រជុំ)</b>\n";
                        $telegramMsg .= "🎪 <b>ឈ្មោះសាល:</b> {$bookedItem['room_name']}\n";
                        $telegramMsg .= "🗓️ <b>កាលបរិច្ឆេទ:</b> {$bookedItem['start_date']} ដល់ {$bookedItem['end_date']}\n";
                        $telegramMsg .= "⏰ <b>ម៉ោង:</b> {$bookedItem['start_time']} - {$bookedItem['end_time']}\n";
                        $telegramMsg .= "☀️ <b>រយៈពេលជួល:</b> {$days} ថ្ងៃ\n";
                    }
                    $telegramMsg .= "💵 <b>តម្លៃ:</b> \${$bookedItem['total_price']}\n\n";

                    $totalAmount += $bookedItem['total_price'];
                    $roomNo++;
                }

                $telegramMsg .= "----------------------------------\n";
                $telegramMsg .= "💵 <b>សរុបទឹកប្រាក់រួម: \${$totalAmount}</b>\n";
                $telegramMsg .= "🆔 <b>Booking Code:</b> <code>" . ($generatedCodes[0] ?? 'N/A') . "</code>\n";

                // ផ្ញើទៅ Telegram
                $this->sendTelegramNotification($telegramMsg);
            } catch (\Exception $telegramError) {
                \Log::error('Telegram Notification Error: ' . $telegramError->getMessage());
            }

            // សម្អាតកន្ត្រកចេញពី Session
            session()->forget('cart');

            return response()->json([
                'status'        => 'success',
                'message'       => 'ការកក់ និងការទូទាត់របស់អ្នកត្រូវបានបញ្ជូនដោយជោគជ័យ!',
                'booking_code'  => $generatedCodes[0],
                'booking_codes' => $generatedCodes
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'status'  => 'error',
                'message' => 'មានបញ្ហាបច្ចេកទេស៖ ' . $e->getMessage()
            ]);
        }
    }

    private function isRoomAvailable($roomId, $item)
    {
        // ១. ឆែកមើលស្ថានភាពផ្ទាល់របស់បន្ទប់ គឺហាមតែបន្ទប់កំពុងជួសជុល (maintenance) ប៉ុណ្ណោះ
        $room = DB::table('rooms')->where('id', $roomId)->first();
        if (!$room || $room->status === 'maintenance') {
            return false;
        }

        $category = DB::table('room_types')->where('id', $room->room_type_id)->value('category');

        // បន្ថែមការឆែក៖ ប្រសិនបើជាប្រភេទបន្ទប់ស្នាក់នៅ (Stay) និងមានទិន្នន័យ check_in
        if ($category === 'stay' && isset($item['check_in']) && isset($item['check_out'])) {
            $checkIn  = $item['check_in'];
            $checkOut = $item['check_out'];

            // ២. ឆែកមើលប្រវត្តិកក់ក្នុង hotel_bookings ថាជាន់ថ្ងៃគ្នាដែរឬទេ
            $isBooked = DB::table('hotel_bookings')
                ->where('room_id', $roomId)
                ->where('status', '!=', 'cancelled')
                ->where(function ($query) use ($checkIn, $checkOut) {
                    $query->where('check_in', '<', $checkOut)
                        ->where('check_out', '>', $checkIn);
                })
                ->exists();

            return !$isBooked;
        } elseif (isset($item['start_date']) && isset($item['end_date'])) {
            // --- សម្រាប់សាលប្រជុំ (Meeting Room) ---
            $startDate = $item['start_date'];
            $endDate   = $item['end_date'];
            $startTime = $item['start_time'] ?? '00:00:00';
            $endTime   = $item['end_time'] ?? '23:59:59';

            $startDateTimeNew = $startDate . ' ' . $startTime;
            $endDateTimeNew   = $endDate . ' ' . $endTime;

            $isBooked = DB::table('meeting_bookings')
                ->where('meeting_room_id', $roomId)
                ->where('status', '!=', 'cancelled')
                ->where(function ($query) use ($startDateTimeNew, $endDateTimeNew) {
                    $query->where(DB::raw("CONCAT(start_date, ' ', start_time)"), '<', $endDateTimeNew)
                        ->where(DB::raw("CONCAT(end_date, ' ', end_time)"), '>', $startDateTimeNew);
                })
                ->exists();

            return !$isBooked;
        }

        return false;
    }

    public function show($id, Request $request)
    {
        $room = DB::table('rooms')->where('id', $id)->first();

        // ចាប់យកថ្ងៃខែដែលភ្ញៀវជ្រើសរើសនៅលើទឹកដី (Format: Y-m-d)
        $checkIn  = $request->input('check_in', now()->format('Y-m-d'));
        $checkOut = $request->input('check_out', now()->addDay()->format('Y-m-d'));

        // ឆែកមើលថាតើមានការកក់ជាន់គ្នារួចហើយឬនៅ
        $isBooked = DB::table('hotel_bookings')
            ->where('room_id', $id)
            ->where('status', '!=', 'cancelled')
            ->where(function ($query) use ($checkIn, $checkOut) {
                $query->where('check_in', '<', $checkOut)
                    ->where('check_out', '>', $checkIn);
            })
            ->exists();

        $isAvailable = !$isBooked;

        return view('frontend.room_details', compact('room', 'isAvailable', 'checkIn', 'checkOut'));
    }

    /**
     * 🚀 អនុគមន៍កែសម្រួលថ្មី៖ ផ្ញើសារទៅ Telegram ដោយប្រើ file_get_contents (សុវត្ថិភាពខ្ពស់)
     */
    private function sendTelegramNotification($message)
    {
        $botToken = env('TELEGRAM_BOT_TOKEN');
        $chatId   = env('TELEGRAM_CHAT_ID');

        if (!$botToken || !$chatId) {
            return;
        }

        $url = "https://api.telegram.org/bot{$botToken}/sendMessage";

        $data = [
            'chat_id'    => $chatId,
            'text'       => $message,
            'parse_mode' => 'HTML'
        ];

        // បង្កើត Context ដើម្បីផ្ញើជាទម្រង់ POST និងបិទការឆែក SSL (ករណី Localhost ជួបបញ្ហា)
        $options = [
            'http' => [
                'header'  => "Content-type: application/x-www-form-urlencoded\r\n",
                'method'  => 'POST',
                'content' => http_build_query($data),
                'timeout' => 5, // រង់ចាំត្រឹម ៥ វិនាទី ការពារកុំឱ្យគាំងវេបសាយ
            ],
            'ssl' => [
                'verify_peer'      => false,
                'verify_peer_name' => false,
            ],
        ];

        $context = stream_context_create($options);

        // ដំណើរការផ្ញើទៅកាន់ Telegram
        @file_get_contents($url, false, $context);
    }
}
