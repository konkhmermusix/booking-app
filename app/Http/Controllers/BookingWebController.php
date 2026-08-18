<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Booking;
use App\Models\RoomType;
use App\Models\Room;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;
use App\Models\BookingDetail;
use App\Models\Payment;
use Illuminate\Support\Facades\DB;

class BookingWebController extends Controller
{
    public function myBookings()
    {
        $userId = Auth::id();

        $hotelBookings = DB::table('hotel_bookings')
            ->leftJoin('payments', 'hotel_bookings.id', '=', 'payments.hotel_booking_id')
            ->leftJoin('rooms', 'hotel_bookings.room_id', '=', 'rooms.id')
            ->leftJoin('room_types', 'rooms.room_type_id', '=', 'room_types.id')
            ->where('hotel_bookings.user_id', $userId)
            ->select(
                'hotel_bookings.*',
                'payments.method as payment_method',
                'payments.status as payment_status',
                'rooms.room_number as room_name',
                'room_types.name as room_type_name'
            )
            ->orderBy('hotel_bookings.created_at', 'desc')
            ->paginate(12, ['*'], 'hotel_page');

        foreach ($hotelBookings as $hb) {
            $types = DB::table('hotel_booking_details')
                ->join('room_types', 'hotel_booking_details.room_type_id', '=', 'room_types.id')
                ->where('hotel_booking_details.hotel_booking_id', $hb->id)
                ->pluck('room_types.name')
                ->toArray();

            if (!empty($types)) {
                $hb->room_type_name = implode(', ', array_unique($types));
            }
        }

        $meetingBookings = DB::table('meeting_bookings')
            ->leftJoin('payments', 'meeting_bookings.id', '=', 'payments.meeting_booking_id')
            ->leftJoin('rooms', 'meeting_bookings.meeting_room_id', '=', 'rooms.id')
            ->leftJoin('room_types', 'rooms.room_type_id', '=', 'room_types.id')
            ->where('meeting_bookings.user_id', $userId)
            ->select(
                'meeting_bookings.*',
                'payments.method as payment_method',
                'payments.status as payment_status',
                'rooms.room_number as room_name',
                'room_types.name as room_type_name'
            )
            ->orderBy('meeting_bookings.created_at', 'desc')
            ->paginate(12, ['*'], 'meeting_page');

        return view('frontend.mybookings', compact('hotelBookings', 'meetingBookings'));
    }

    public function viewReceipt($code, Request $request)
    {
        $rawCodes = $request->input('codes', $code);
        $codes = array_filter(array_unique(explode(',', $rawCodes)));

        $hotelBookings = DB::table('hotel_bookings')
            ->whereIn('booking_code', $codes)
            ->get();

        $meetingBookings = DB::table('meeting_bookings')
            ->whereIn('booking_code', $codes)
            ->get();

        if ($hotelBookings->isEmpty() && $meetingBookings->isEmpty()) {
            return redirect()->route('home')->with('error', 'រកមិនឃើញវិក្កយបត្រដែលអ្នកស្វែងរកឡើយ');
        }

        $allReceiptItems = collect();
        $grandTotal = 0;
        $customerName = Auth::check() ? Auth::user()->name : null;
        $customerPhone = Auth::check() ? Auth::user()->phone : null;
        $customerEmail = Auth::check() ? Auth::user()->email : null;
        $payment = null;

        // Process hotel bookings
        foreach ($hotelBookings as $hb) {
            $grandTotal += $hb->total_price;
            if (!$customerName && !empty($hb->customer_name)) $customerName = $hb->customer_name;
            if (!$customerPhone && !empty($hb->customer_phone)) $customerPhone = $hb->customer_phone;
            if (!$customerEmail && !empty($hb->customer_email)) $customerEmail = $hb->customer_email;

            if (!$payment) {
                $payment = DB::table('payments')
                    ->where('hotel_booking_id', $hb->id)
                    ->select('payments.*', 'payments.status as payment_status')
                    ->first();
            }

            $detailsList = DB::table('hotel_booking_details')
                ->join('rooms', 'hotel_booking_details.room_id', '=', 'rooms.id')
                ->join('room_types', 'rooms.room_type_id', '=', 'room_types.id')
                ->where('hotel_booking_details.hotel_booking_id', $hb->id)
                ->select('rooms.room_number as name', 'room_types.name as type_name', 'hotel_booking_details.price_at_booking')
                ->get();

            if ($detailsList->count() > 0) {
                foreach ($detailsList as $dt) {
                    $allReceiptItems->push((object)[
                        'item_type'    => 'hotel',
                        'booking_code' => $hb->booking_code,
                        'type_name'    => $dt->type_name ?? 'បន្ទប់ស្នាក់នៅ',
                        'name'         => $dt->name ?? '',
                        'check_in'     => $hb->check_in,
                        'check_out'    => $hb->check_out,
                        'price'        => $dt->price_at_booking ?? ($hb->total_price / $detailsList->count()),
                    ]);
                }
            } else {
                $allReceiptItems->push((object)[
                    'item_type'    => 'hotel',
                    'booking_code' => $hb->booking_code,
                    'type_name'    => 'បន្ទប់ស្នាក់នៅ',
                    'name'         => '',
                    'check_in'     => $hb->check_in,
                    'check_out'    => $hb->check_out,
                    'price'        => $hb->total_price,
                ]);
            }
        }

        // Process meeting bookings
        foreach ($meetingBookings as $mb) {
            $grandTotal += $mb->total_price;
            if (!$customerName && !empty($mb->customer_name)) $customerName = $mb->customer_name;
            if (!$customerPhone && !empty($mb->customer_phone)) $customerPhone = $mb->customer_phone;
            if (!$customerEmail && !empty($mb->customer_email)) $customerEmail = $mb->customer_email;

            if (!$payment) {
                $payment = DB::table('payments')
                    ->where('meeting_booking_id', $mb->id)
                    ->select('payments.*', 'payments.status as payment_status')
                    ->first();
            }

            $details = DB::table('rooms')
                ->join('room_types', 'rooms.room_type_id', '=', 'room_types.id')
                ->where('rooms.id', $mb->meeting_room_id)
                ->select('rooms.room_number as name', 'room_types.name as type_name')
                ->first();

            $allReceiptItems->push((object)[
                'item_type'    => 'meeting',
                'booking_code' => $mb->booking_code,
                'type_name'    => $details->type_name ?? 'សាលប្រជុំ',
                'name'         => $details->name ?? '',
                'start_date'   => $mb->start_date,
                'start_time'   => $mb->start_time,
                'end_time'     => $mb->end_time,
                'price'        => $mb->total_price,
            ]);
        }

        $booking = $hotelBookings->first() ?? $meetingBookings->first();
        $primaryCode = implode(', ', $codes);
        $type = (count($hotelBookings) > 0 && count($meetingBookings) > 0) ? 'combined' : (count($hotelBookings) > 0 ? 'hotel' : 'meeting');

        return view('frontend.receipt', compact(
            'booking',
            'codes',
            'primaryCode',
            'type',
            'payment',
            'allReceiptItems',
            'grandTotal',
            'customerName',
            'customerPhone',
            'customerEmail'
        ));
    }

    public function downloadPdf($code, Request $request)
    {
        $paperSize = strtolower($request->input('size', 'a4'));
        if (!in_array($paperSize, ['a4', 'a5'])) {
            $paperSize = 'a4';
        }

        $rawCodes = $request->input('codes', $code);
        $codes = array_filter(array_unique(explode(',', $rawCodes)));

        $hotelBookings = DB::table('hotel_bookings')
            ->whereIn('booking_code', $codes)
            ->get();

        $meetingBookings = DB::table('meeting_bookings')
            ->whereIn('booking_code', $codes)
            ->get();

        if ($hotelBookings->isEmpty() && $meetingBookings->isEmpty()) {
            return redirect()->route('home')->with('error', 'រកមិនឃើញវិក្កយបត្រដែលអ្នកស្វែងរកឡើយ');
        }

        $allReceiptItems = collect();
        $grandTotal = 0;
        $customerName = Auth::check() ? Auth::user()->name : null;
        $customerPhone = Auth::check() ? Auth::user()->phone : null;
        $customerEmail = Auth::check() ? Auth::user()->email : null;
        $payment = null;

        // Process hotel bookings
        foreach ($hotelBookings as $hb) {
            $grandTotal += $hb->total_price;
            if (!$customerName && !empty($hb->customer_name)) $customerName = $hb->customer_name;
            if (!$customerPhone && !empty($hb->customer_phone)) $customerPhone = $hb->customer_phone;
            if (!$customerEmail && !empty($hb->customer_email)) $customerEmail = $hb->customer_email;

            if (!$payment) {
                $payment = DB::table('payments')
                    ->where('hotel_booking_id', $hb->id)
                    ->select('payments.*', 'payments.status as payment_status')
                    ->first();
            }

            $detailsList = DB::table('hotel_booking_details')
                ->join('rooms', 'hotel_booking_details.room_id', '=', 'rooms.id')
                ->join('room_types', 'rooms.room_type_id', '=', 'room_types.id')
                ->where('hotel_booking_details.hotel_booking_id', $hb->id)
                ->select('rooms.room_number as name', 'room_types.name as type_name', 'hotel_booking_details.price_at_booking')
                ->get();

            if ($detailsList->count() > 0) {
                foreach ($detailsList as $dt) {
                    $allReceiptItems->push((object)[
                        'item_type'    => 'hotel',
                        'booking_code' => $hb->booking_code,
                        'type_name'    => $dt->type_name ?? 'បន្ទប់ស្នាក់នៅ',
                        'name'         => $dt->name ?? '',
                        'check_in'     => $hb->check_in,
                        'check_out'    => $hb->check_out,
                        'price'        => $dt->price_at_booking ?? ($hb->total_price / $detailsList->count()),
                    ]);
                }
            } else {
                $allReceiptItems->push((object)[
                    'item_type'    => 'hotel',
                    'booking_code' => $hb->booking_code,
                    'type_name'    => 'បន្ទប់ស្នាក់នៅ',
                    'name'         => '',
                    'check_in'     => $hb->check_in,
                    'check_out'    => $hb->check_out,
                    'price'        => $hb->total_price,
                ]);
            }
        }

        // Process meeting bookings
        foreach ($meetingBookings as $mb) {
            $grandTotal += $mb->total_price;
            if (!$customerName && !empty($mb->customer_name)) $customerName = $mb->customer_name;
            if (!$customerPhone && !empty($mb->customer_phone)) $customerPhone = $mb->customer_phone;
            if (!$customerEmail && !empty($mb->customer_email)) $customerEmail = $mb->customer_email;

            if (!$payment) {
                $payment = DB::table('payments')
                    ->where('meeting_booking_id', $mb->id)
                    ->select('payments.*', 'payments.status as payment_status')
                    ->first();
            }

            $details = DB::table('rooms')
                ->join('room_types', 'rooms.room_type_id', '=', 'room_types.id')
                ->where('rooms.id', $mb->meeting_room_id)
                ->select('rooms.room_number as name', 'room_types.name as type_name')
                ->first();

            $allReceiptItems->push((object)[
                'item_type'    => 'meeting',
                'booking_code' => $mb->booking_code,
                'type_name'    => $details->type_name ?? 'សាលប្រជុំ',
                'name'         => $details->name ?? '',
                'start_date'   => $mb->start_date,
                'start_time'   => $mb->start_time,
                'end_time'     => $mb->end_time,
                'price'        => $mb->total_price,
            ]);
        }

        $booking = $hotelBookings->first() ?? $meetingBookings->first();
        $primaryCode = implode(', ', $codes);
        $type = (count($hotelBookings) > 0 && count($meetingBookings) > 0) ? 'combined' : (count($hotelBookings) > 0 ? 'hotel' : 'meeting');

        $data = compact(
            'booking',
            'codes',
            'primaryCode',
            'type',
            'payment',
            'allReceiptItems',
            'grandTotal',
            'customerName',
            'customerPhone',
            'customerEmail',
            'paperSize'
        );

        if (class_exists(\Barryvdh\DomPDF\Facade\Pdf::class)) {
            $pdf = \Barryvdh\DomPDF\Facade\Pdf::loadView('frontend.receipt_pdf', $data);
            $pdf->setPaper($paperSize, 'portrait');
            $pdf->setOption([
                'isRemoteEnabled' => true,
                'isFontSubsettingEnabled' => true,
                'defaultFont' => 'KantumruyPro'
            ]);
            return $pdf->download('Invoice-' . $primaryCode . '-' . strtoupper($paperSize) . '.pdf');
        }

        return view('frontend.receipt_pdf', $data);
    }

    public function cancelBooking($id, Request $request)
    {
        $userId = Auth::id();

        $hotelBooking = DB::table('hotel_bookings')
            ->where('id', $id)
            ->where('user_id', $userId)
            ->first();

        if ($hotelBooking) {
            if (in_array($hotelBooking->status, ['pending', 'confirmed'])) {
                DB::table('hotel_bookings')->where('id', $id)->update(['status' => 'cancelled']);
                DB::table('rooms')->where('id', $hotelBooking->room_id)->update(['status' => 'available']);
                return back()->with('success', 'ការកក់បន្ទប់ត្រូវបានបោះបង់ដោយជោគជ័យ');
            }
            return back()->with('error', 'ការកក់នេះមិនអាចបោះបង់បានឡើយ');
        }

        // ឆែកមើលការកក់សាលប្រជុំ
        $meetingBooking = DB::table('meeting_bookings')
            ->where('id', $id)
            ->where('user_id', $userId)
            ->first();

        if ($meetingBooking) {
            if (in_array($meetingBooking->status, ['pending', 'confirmed'])) {
                DB::table('meeting_bookings')->where('id', $id)->update(['status' => 'cancelled']);
                DB::table('rooms')->where('id', $meetingBooking->meeting_room_id)->update(['status' => 'available']);
                return back()->with('success', 'ការកក់សាលប្រជុំត្រូវបានបោះបង់ដោយជោគជ័យ');
            }
            return back()->with('error', 'ការកក់នេះមិនអាចបោះបង់បានឡើយ');
        }

        return back()->with('error', 'រកមិនឃើញការកក់ឡើយ');
    }

    public function bookingSuccess($code, Request $request)
    {
        $rawCodes = $request->input('codes', $code);
        $codes = array_filter(array_unique(explode(',', $rawCodes)));

        $hotelBookings = DB::table('hotel_bookings')
            ->whereIn('booking_code', $codes)
            ->get();

        $meetingBookings = DB::table('meeting_bookings')
            ->whereIn('booking_code', $codes)
            ->get();

        if ($hotelBookings->isEmpty() && $meetingBookings->isEmpty()) {
            return redirect()->route('home')->with('error', 'រកមិនឃើញទិន្នន័យការកក់ឡើយ');
        }

        $allItems = [];
        $grandTotal = 0;
        $customerName = Auth::check() ? Auth::user()->name : null;
        $customerPhone = Auth::check() ? Auth::user()->phone : null;
        $customerEmail = Auth::check() ? Auth::user()->email : null;
        $paymentMethod = null;

        foreach ($hotelBookings as $hb) {
            $grandTotal += $hb->total_price;
            if (!$customerName && !empty($hb->customer_name)) $customerName = $hb->customer_name;
            if (!$customerPhone && !empty($hb->customer_phone)) $customerPhone = $hb->customer_phone;
            if (!$customerEmail && !empty($hb->customer_email)) $customerEmail = $hb->customer_email;

            $pm = DB::table('payments')->where('hotel_booking_id', $hb->id)->value('method');
            if ($pm) $paymentMethod = $pm;

            $detailsList = DB::table('hotel_booking_details')
                ->join('rooms', 'hotel_booking_details.room_id', '=', 'rooms.id')
                ->join('room_types', 'rooms.room_type_id', '=', 'room_types.id')
                ->where('hotel_booking_details.hotel_booking_id', $hb->id)
                ->select('rooms.room_number as room_name', 'room_types.name as type_name', 'hotel_booking_details.price_at_booking')
                ->get();

            if ($detailsList->count() > 0) {
                foreach ($detailsList as $dt) {
                    $allItems[] = [
                        'type'        => 'hotel',
                        'code'        => $hb->booking_code,
                        'name'        => $dt->type_name ?? 'បន្ទប់ស្នាក់នៅ',
                        'room_number' => $dt->room_name ?? '',
                        'check_in'    => $hb->check_in,
                        'check_out'   => $hb->check_out,
                        'total_price' => $dt->price_at_booking ?? ($hb->total_price / $detailsList->count()),
                    ];
                }
            } else {
                $allItems[] = [
                    'type'        => 'hotel',
                    'code'        => $hb->booking_code,
                    'name'        => 'បន្ទប់ស្នាក់នៅ',
                    'room_number' => '',
                    'check_in'    => $hb->check_in,
                    'check_out'   => $hb->check_out,
                    'total_price' => $hb->total_price,
                ];
            }
        }

        foreach ($meetingBookings as $mb) {
            $grandTotal += $mb->total_price;
            if (!$customerName && !empty($mb->customer_name)) $customerName = $mb->customer_name;
            if (!$customerPhone && !empty($mb->customer_phone)) $customerPhone = $mb->customer_phone;
            if (!$customerEmail && !empty($mb->customer_email)) $customerEmail = $mb->customer_email;

            $pm = DB::table('payments')->where('meeting_booking_id', $mb->id)->value('method');
            if ($pm) $paymentMethod = $pm;

            $details = DB::table('rooms')
                ->join('room_types', 'rooms.room_type_id', '=', 'room_types.id')
                ->where('rooms.id', $mb->meeting_room_id)
                ->select('rooms.room_number as room_name', 'room_types.name as type_name')
                ->first();

            $allItems[] = [
                'type'        => 'meeting',
                'code'        => $mb->booking_code,
                'name'        => $details->type_name ?? 'សាលប្រជុំ',
                'room_number' => $details->room_name ?? '',
                'start_date'  => $mb->start_date,
                'end_date'    => $mb->end_date,
                'start_time'  => $mb->start_time,
                'end_time'    => $mb->end_time,
                'total_price' => $mb->total_price,
            ];
        }

        $booking = $hotelBookings->first() ?? $meetingBookings->first();
        $primaryCode = implode(', ', $codes);

        return view('frontend.booking_success', compact(
            'booking',
            'codes',
            'primaryCode',
            'allItems',
            'grandTotal',
            'customerName',
            'customerPhone',
            'customerEmail',
            'paymentMethod'
        ));
    }
}
