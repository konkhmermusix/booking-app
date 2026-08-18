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
            return redirect()->route('cart.index')->with('error', 'កន្ត្រករបស់អ្នកទំនេរ សូមជ្រើសរើសបន្ទប់សិន');
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
            'phone'          => 'required|string|max:20',
            'email'          => 'required|email',
            'payment_method' => 'required|string',
            'payment_slip'   => 'nullable|string'
        ]);

        $cart = session()->get('cart', []);
        if (empty($cart)) {
            return response()->json(['status' => 'error', 'message' => 'កន្ត្រកទំនេរ']);
        }

        try {
            DB::beginTransaction();
            $userId = Auth::id();
            $generatedCodes = [];
            $paymentMethodEnum = ($request->payment_method === 'cash') ? 'cash' : 'qr';
            $slipPath = null;

            if ($paymentMethodEnum === 'qr') {
                if (!$request->payment_slip) {
                    DB::rollBack();
                    return response()->json([
                        'status'  => 'error',
                        'message' => 'សូមអាប់ឡូតរូបភាពបង្កាន់ដៃបង់ប្រាក់ជាមុនសិន'
                    ]);
                }

                $slipPath = $this->uploadBase64($request->payment_slip, 'slips');

                $cartTotal = array_sum(array_column($cart, 'total_price'));

                if ($slipPath && file_exists(storage_path('app/public/' . $slipPath))) {
                    $photoFullPath = storage_path('app/public/' . $slipPath);
                    $ocrService = new \App\Services\SlipOcrService();
                    $ocrResult = $ocrService->verifySlip($photoFullPath, $cartTotal);

                    if ($ocrResult['status'] === 'fake') {
                        DB::rollBack();
                        @unlink($photoFullPath);
                        return response()->json([
                            'status'  => 'error',
                            'message' => $ocrResult['message'] ?? "រូបភាពដែលលោកអ្នកបានអាប់ឡូត មិនមែនជា «បង្កាន់ដៃទូទាត់ប្រាក់ធនាគារ» ពិតប្រាកដឡើយ សូមអាប់ឡូតរូបភាពបង្កាន់ដៃបង់ប្រាក់ដែលទទួលបានពី Mobile Banking App (ABA, ACLEDA, Wing, Bakong...)"
                        ]);
                    }

                    if ($ocrResult['status'] === 'mismatch') {
                        DB::rollBack();
                        @unlink($photoFullPath);
                        return response()->json([
                            'status'  => 'error',
                            'message' => $ocrResult['message'] ?? "បង្កាន់ដៃបង់ប្រាក់របស់អ្នកមិនត្រូវគ្នានឹងចំនួនទឹកប្រាក់ត្រូវបង់ (\${$cartTotal}) ឡើយ សូមពិនិត្យមើលរូបភាពបង្កាន់ដៃបង់ប្រាក់ឡើងវិញ ឬអាប់ឡូតរូបភាពបង្កាន់ដៃបង់ប្រាក់ឱ្យគ្រប់ចំនួន"
                        ]);
                    }

                    if ($ocrResult['status'] === 'wrong_account') {
                        DB::rollBack();
                        @unlink($photoFullPath);
                        return response()->json([
                            'status'  => 'error',
                            'message' => $ocrResult['message'] ?? "រូបភាពបង្កាន់ដៃបង់ប្រាក់របស់អ្នកទូទាត់ទៅគណនីខុស សូមស្គែនឃ្យូអរកូដត្រឹមត្រូវ ហើយទូទាត់ម្ដងទៀត"
                        ]);
                    }

                    if ($ocrResult['status'] !== 'exact') {
                        DB::rollBack();
                        @unlink($photoFullPath);
                        return response()->json([
                            'status'  => 'error',
                            'message' => $ocrResult['message'] ?? "សូមអធ្យាស្រ័យផង យើងមិនអាចផ្ទៀងផ្ទាត់រូបភាពបង្កាន់ដៃបង់ប្រាក់របស់អ្នកបានឡើយ សូមអាប់ឡូតរូបភាពបង្កាន់ដៃបង់ប្រាក់ច្បាស់លាស់ដែលបានទូទាត់ប្រាក់នៅថ្ងៃនេះ ឱ្យបានគ្រប់ចំនួន (\${$cartTotal})"
                        ]);
                    }
                }
            }

            foreach ($cart as $item) {
                $roomId = $item['id'];
                if (!$this->isRoomAvailable($roomId, $item)) {
                    DB::rollBack();
                    return response()->json([
                        'status'  => 'error',
                        'message' => "សុំទោសផង បន្ទប់ដែលអ្នកបានជ្រើសរើសត្រូវបានគេកក់រួចហើយនៅក្នុងកាលបរិច្ឆេទនេះ។ សូមជ្រើសរើសថ្ងៃខែ ឬបន្ទប់ផ្សេង"
                    ]);
                }
            }

            $hotelItems   = array_filter($cart, fn($i) => ($i['type'] ?? 'hotel') === 'hotel');
            $meetingItems = array_filter($cart, fn($i) => ($i['type'] ?? 'hotel') === 'meeting');

            $bookedItemsForTelegram = [];

            if (!empty($hotelItems)) {
                $bookingCode = 'PNT-' . strtoupper(Str::random(6));
                $generatedCodes[] = $bookingCode;

                $firstHotelItem = reset($hotelItems);
                $firstRoomId    = $firstHotelItem['id'];
                $hotelId        = DB::table('rooms')->where('id', $firstRoomId)->value('hotel_id');

                $totalHotelPrice = array_sum(array_column($hotelItems, 'total_price'));

                $hotelBookingId = DB::table('hotel_bookings')->insertGetId([
                    'user_id'          => $userId,
                    'hotel_id'         => $hotelId,
                    'room_id'          => $firstRoomId,
                    'customer_name'    => $request->name,
                    'customer_phone'   => $request->phone,
                    'customer_email'   => $request->email,
                    'check_in'         => $firstHotelItem['check_in'] ?? null,
                    'check_out'        => $firstHotelItem['check_out'] ?? null,
                    'check_in_time'    => $firstHotelItem['check_in_time'] ?? null,
                    'check_out_time'   => $firstHotelItem['check_out_time'] ?? null,
                    'total_price'      => $totalHotelPrice,
                    'status'           => 'pending',
                    'booking_code'     => $bookingCode,
                    'special_requests' => $request->special_requests ?? null,
                    'created_at'       => now(),
                    'updated_at'       => now(),
                ]);

                foreach ($hotelItems as $item) {
                    $rId = $item['id'];
                    $rTypeId = DB::table('rooms')->where('id', $rId)->value('room_type_id');
                    $rTypeName = DB::table('room_types')->where('id', $rTypeId)->value('name') ?? 'មិនស្គាល់';

                    DB::table('hotel_booking_details')->insert([
                        'hotel_booking_id' => $hotelBookingId,
                        'room_id'          => $rId,
                        'room_type_id'     => $rTypeId,
                        'price_at_booking' => $item['price'] ?? ($item['total_price'] / ($item['total_nights'] ?? 1)),
                        'created_at'       => now(),
                        'updated_at'       => now(),
                    ]);

                    $bookedItemsForTelegram[] = [
                        'type'         => 'hotel',
                        'booking_code' => $bookingCode,
                        'room_type'    => $rTypeName,
                        'check_in'     => $item['check_in'],
                        'check_out'    => $item['check_out'],
                        'total_price'  => $item['total_price']
                    ];
                }

                DB::table('payments')->insert([
                    'hotel_booking_id'   => $hotelBookingId,
                    'meeting_booking_id' => null,
                    'method'             => $paymentMethodEnum,
                    'amount'             => $totalHotelPrice,
                    'currency'           => 'USD',
                    'transaction_id'     => ($paymentMethodEnum === 'qr') ? 'TXN-' . strtoupper(Str::random(10)) : null,
                    'payment_slip'       => $slipPath,
                    'status'             => 'pending',
                    'paid_at'            => null, // ទុក NULL សិន ដើម្បីរង់ចាំ Admin ពិនិត្យមើល Slip / ទទួលសាច់ប្រាក់ ទើបប្តូរទៅ paid
                    'created_at'         => now(),
                    'updated_at'         => now(),
                ]);
            }

            // === ដំណើរការកក់សាលប្រជុំ (MEETING ROOMS) ===
            if (!empty($meetingItems)) {
                foreach ($meetingItems as $item) {
                    $mBookingCode = 'PNT-' . strtoupper(Str::random(6));
                    $generatedCodes[] = $mBookingCode;

                    $mRoomId = $item['id'];
                    $meetingRoomName = DB::table('rooms')->where('id', $mRoomId)->value('room_number') ?? 'មិនស្គាល់';

                    $meetingBookingId = DB::table('meeting_bookings')->insertGetId([
                        'user_id'          => $userId,
                        'meeting_room_id'  => $mRoomId,
                        'start_date'       => $item['start_date'],
                        'end_date'         => $item['end_date'],
                        'start_time'       => $item['start_time'],
                        'end_time'         => $item['end_time'],
                        'total_hours'      => $item['total_hours'] ?? null,
                        'total_price'      => $item['total_price'],
                        'status'           => 'pending',
                        'booking_code'     => $mBookingCode,
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
                        'transaction_id'     => ($paymentMethodEnum === 'qr') ? 'TXN-' . strtoupper(Str::random(10)) : null,
                        'payment_slip'       => $slipPath,
                        'status'             => 'pending',
                        'paid_at'            => null,
                        'created_at'         => now(),
                        'updated_at'         => now(),
                    ]);

                    $bookedItemsForTelegram[] = [
                        'type'         => 'meeting',
                        'booking_code' => $mBookingCode,
                        'room_name'    => $meetingRoomName,
                        'start_date'   => $item['start_date'],
                        'end_date'     => $item['end_date'],
                        'start_time'   => $item['start_time'],
                        'end_time'     => $item['end_time'],
                        'total_price'  => $item['total_price']
                    ];
                }
            }

            DB::commit();

            // ផ្នែកផ្ញើសារជូនដំណឹងទៅកាន់គ្រុប Telegram 
            try {
                $paymentText = ($paymentMethodEnum === 'qr') ? 'ស្កែនឃ្យូអរកូដ' : 'ទូទាត់សាច់ប្រាក់ផ្ទាល់';
                $specialRequests = $request->special_requests ? $request->special_requests : 'គ្មាន';

                $telegramMsg = "🔔 <b>មានការកក់ថ្មីពីគេហទំព័រ</b>\n";
                $telegramMsg .= "----------------------------------\n";
                $telegramMsg .= "👤 <b>ឈ្មោះភ្ញៀវ:</b> {$request->name}\n";
                $telegramMsg .= "📞 <b>លេខទូរស័ព្ទ:</b> {$request->phone}\n";
                $telegramMsg .= "✉️ <b>អ៊ីមែល:</b> {$request->email}\n";
                $telegramMsg .= "💰 <b>វិធីទូទាត់:</b> {$paymentText}\n";
                $telegramMsg .= "📝 <b>មតិផ្សេងៗ:</b> {$specialRequests}\n";
                $telegramMsg .= "----------------------------------\n";

                $roomNo = 1;
                $totalAmount = 0;

                foreach ($bookedItemsForTelegram as $bookedItem) {
                    $itemCode = $bookedItem['booking_code'] ?? 'N/A';

                    if ($bookedItem['type'] == 'hotel') {
                        // គណនាចំនួនយប់
                        $checkInDate  = \Carbon\Carbon::parse($bookedItem['check_in']);
                        $checkOutDate = \Carbon\Carbon::parse($bookedItem['check_out']);
                        $nights       = $checkInDate->diffInDays($checkOutDate);
                        $nights       = $nights == 0 ? 1 : $nights;

                        $telegramMsg .= "🏨 <b>ការកក់ទី {$roomNo}: (បន្ទប់សណ្ឋាគារ)</b>\n";
                        $telegramMsg .= "🆔 <b>Booking Code:</b> <code>{$itemCode}</code>\n";
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
                        $telegramMsg .= "🆔 <b>Booking Code:</b> <code>{$itemCode}</code>\n";
                        $telegramMsg .= "🎪 <b>ឈ្មោះសាល:</b> {$bookedItem['room_name']}\n";
                        $telegramMsg .= "🗓️ <b>កាលបរិច្ឆេទ:</b> {$bookedItem['start_date']} ដល់ {$bookedItem['end_date']}\n";
                        $telegramMsg .= "⏰ <b>ម៉ោង:</b> {$bookedItem['start_time']} - {$bookedItem['end_time']}\n";
                        $telegramMsg .= "☀️ <b>រយៈពេលជួល:</b> {$days} ថ្ងៃ\n";
                    }
                    $telegramMsg .= "💵 <b>តម្លៃ:</b> \${$bookedItem['total_price']}\n\n";

                    $totalAmount += $bookedItem['total_price'];
                    $roomNo++;
                }

                $allCodesStr = implode(', ', $generatedCodes);
                $telegramMsg .= "----------------------------------\n";
                $telegramMsg .= "💵 <b>សរុបទឹកប្រាក់រួម: \${$totalAmount}</b>\n";
                $telegramMsg .= "🆔 <b>លេខកូដកក់:</b> <code>{$allCodesStr}</code>\n";

                $photoFullPath = null;
                if ($slipPath && file_exists(storage_path('app/public/' . $slipPath))) {
                    $photoFullPath = storage_path('app/public/' . $slipPath);

                    $ocrService = new \App\Services\SlipOcrService();
                    $ocrResult = $ocrService->verifySlip($photoFullPath, $totalAmount);

                    $telegramMsg .= "----------------------------------\n";
                    $telegramMsg .= "លទ្ធផលបានផ្ទៀងផ្ទាត់:\n";
                    $telegramMsg .= "{$ocrResult['message']}\n";
                    $telegramMsg .= "សូមចូលទៅប្រព័ន្ធដើម្បីបញ្ជាក់ការកក់នេះបន្ថែមទៀត\n";
                }

                $this->sendTelegramNotification($telegramMsg, $photoFullPath);
            } catch (\Exception $telegramError) {
                \Log::error('Telegram Notification Error: ' . $telegramError->getMessage());
            }

            // ផ្នែកផ្ញើអ៊ីមែលបញ្ជាក់ការកក់ទៅភ្ញៀវ 
            try {
                $emailBody = "សួស្តី {$request->name},\n\n"
                    . "ការកក់របស់ {$request->name}, នៅ សណ្ឋាគារ ភីអេនធី ផាលេស ត្រូវបានបញ្ជូនដោយជោគជ័យ\n\n"
                    . "លេខកូដកក់: " . implode(', ', $generatedCodes) . "\n"
                    . "ឈ្មោះអតិថិជន: {$request->name}\n"
                    . "លេខទូរស័ព្ទ: {$request->phone}\n"
                    . "វិធីទូទាត់: {$paymentText}\n\n"
                    . "សូមអរគុណសម្រាប់ការជ្រើសរើសសណ្ឋាគាររបស់យើងខ្ញុំ យើងខ្ញុំនឹងបញ្ជាក់ព័ត៌មានបន្ថែមក្នុងពេលឆាប់ៗនេះ។\n\n"
                    . "ដោយការគោរពពី,\n"
                    . "សណ្ឋាគារ ភីអេនធី ផាលេស";

                \Illuminate\Support\Facades\Mail::raw($emailBody, function ($message) use ($request) {
                    $message->to($request->email)
                        ->subject('ការបញ្ជាក់ការកក់បន្ទប់ - សណ្ឋាគារ ភីអេនធី ផាលេស');
                });
            } catch (\Exception $mailError) {
                \Log::error('Booking Email Confirmation Error: ' . $mailError->getMessage());
            }

            session()->forget('cart');

            return response()->json([
                'status'        => 'success',
                'message'       => 'ការកក់ និងការទូទាត់របស់អ្នកត្រូវបានបញ្ជូនដោយជោគជ័យ',
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
        $room = DB::table('rooms')->where('id', $roomId)->first();
        if (!$room || $room->status === 'maintenance') {
            return false;
        }

        $category = DB::table('room_types')->where('id', $room->room_type_id)->value('category');

        if ($category === 'stay' && isset($item['check_in']) && isset($item['check_out'])) {
            $checkIn  = $item['check_in'];
            $checkOut = $item['check_out'];

            $isBooked = DB::table('hotel_bookings')
                ->whereIn('status', ['confirmed', 'pending'])
                ->where(function ($query) use ($checkIn, $checkOut) {
                    $query->where('check_in', '<', $checkOut)
                        ->where('check_out', '>', $checkIn);
                })
                ->where(function ($query) use ($roomId) {
                    $query->where('room_id', $roomId)
                        ->orWhereExists(function ($sub) use ($roomId) {
                            $sub->select(DB::raw(1))
                                ->from('hotel_booking_details')
                                ->whereColumn('hotel_booking_details.hotel_booking_id', 'hotel_bookings.id')
                                ->where('hotel_booking_details.room_id', $roomId);
                        });
                })
                ->exists();

            return !$isBooked;
        } elseif (isset($item['start_date']) && isset($item['end_date'])) {
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

        $checkIn  = $request->input('check_in', now()->format('Y-m-d'));
        $checkOut = $request->input('check_out', now()->addDay()->format('Y-m-d'));

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

    // ផ្ញើសារ ឬរូបភាពបង្កាន់ដៃបង់ប្រាក់ទៅកាន់តេឡេក្រាម
    private function sendTelegramNotification($message, $photoPath = null)
    {
        $botToken = config('services.telegram.bot_token');
        $chatId   = config('services.telegram.chat_id');

        if (!$botToken || !$chatId) {
            return;
        }

        if ($photoPath && file_exists($photoPath)) {
            try {
                $url = "https://api.telegram.org/bot{$botToken}/sendPhoto";
                $postFields = [
                    'chat_id'    => $chatId,
                    'caption'    => $message,
                    'parse_mode' => 'HTML',
                    'photo'      => new \CURLFile(realpath($photoPath))
                ];

                $ch = curl_init();
                curl_setopt($ch, CURLOPT_URL, $url);
                curl_setopt($ch, CURLOPT_POST, 1);
                curl_setopt($ch, CURLOPT_POSTFIELDS, $postFields);
                curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
                curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
                curl_setopt($ch, CURLOPT_TIMEOUT, 10);
                $result = curl_exec($ch);
                curl_close($ch);
                return;
            } catch (\Exception $e) {
                \Log::error('Telegram SendPhoto Error: ' . $e->getMessage());
            }
        }

        $url = "https://api.telegram.org/bot{$botToken}/sendMessage";
        $data = [
            'chat_id'    => $chatId,
            'text'       => $message,
            'parse_mode' => 'HTML'
        ];

        $options = [
            'http' => [
                'header'  => "Content-type: application/x-www-form-urlencoded\r\n",
                'method'  => 'POST',
                'content' => http_build_query($data),
                'timeout' => 5,
            ],
            'ssl' => [
                'verify_peer'      => false,
                'verify_peer_name' => false,
            ],
        ];

        $context = stream_context_create($options);
        @file_get_contents($url, false, $context);
    }
}
