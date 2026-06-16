<?php

namespace App\Services;

use App\Repositories\BookingRepository;
use App\Repositories\RoomRepository;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use App\Models\Room;
use App\Models\MeetingRoomBooking; // បើមាន Model សម្រាប់តារាងទី៣

class BookingService
{
    protected $bookingRepo;
    protected $roomRepo;

    public function __construct(
        BookingRepository $bookingRepo,
        RoomRepository $roomRepo
    ) {
        $this->bookingRepo = $bookingRepo;
        $this->roomRepo = $roomRepo;
    }

    public function getAllBookings(array $filters = [])
    {
        return $this->bookingRepo->getAllBookings($filters);
    }

    public function createBooking(array $data)
    {
        return DB::transaction(function () use ($data) {

            // រៀបចំទម្រង់អត្ថបទសម្រាប់កត់ចូល special_requests
            $walkInInfo = "【ភ្ញៀវ Walk-in】 ឈ្មោះ: " . $data['customer_name'] . " | លេខទូរស័ព្ទ: " . $data['customer_phone'];
            $finalRequests = !empty($data['special_requests'])
                ? $walkInInfo . " | សំណូមពរ: " . $data['special_requests']
                : $walkInInfo;

            $bookingCode = 'PNT-' . strtoupper(Str::random(6));

            // កាត់ខណ្ឌចែកទៅតាមប្រភេទនៃការកក់
            if ($data['booking_category'] === 'hotel') {

                // ១. បញ្ចូលទៅតារាងទី១ (hotel_bookings)
                $booking = $this->bookingRepo->create([
                    'booking_code'     => $bookingCode,
                    'hotel_id'         => $data['hotel_id'],
                    'user_id'          => null, // Table ១ អនុញ្ញាតឱ្យ NULL
                    'room_id'          => $data['room_id'],
                    'check_in'         => $data['check_in'],
                    'check_out'        => $data['check_out'],
                    'check_in_time'    => '14:00:00',
                    'check_out_time'   => '12:00:00',
                    'total_price'      => $data['total_price'],
                    'payment_method'   => $data['payment_method'],
                    'special_requests' => $finalRequests,
                    'status'           => 'confirmed',
                ]);

                // ២. បញ្ចូលទៅតារាងទី២ (hotel_booking_details)
                $room = Room::findOrFail($data['room_id']);
                $booking->details()->create([
                    'room_id'          => $room->id,
                    'room_type_id'     => $room->room_type_id,
                    'price_at_booking' => $data['total_price'],
                ]);

                // ៣. បច្ចុប្បន្នភាពស្ថានភាពបន្ទប់
                $this->roomRepo->updateStatus($data['room_id'], 'booked');

                return $booking;
            } else if ($data['booking_category'] === 'meeting_room') {

                // ករណីកក់បន្ទប់ប្រជុំ (តារាងទី៣)
                // ដោយសារ user_id ក្នុង Table ៣ គឺ NOT NULL យើងត្រូវប្រើតួលេខ 0 ឬ ID របស់ Admin ជំនួស
                $meetingBooking = DB::table('meeting_room_bookings')->insertGetId([
                    'booking_code'     => $bookingCode,
                    'user_id'          => 0, // ដាក់ 0 តំណាងឱ្យភ្ញៀវក្រៅប្រព័ន្ធ (Walk-in)
                    'meeting_room_id'  => $data['meeting_room_id'],
                    'start_date'       => $data['start_date'],
                    'end_date'         => $data['end_date'],
                    'start_time'       => $data['start_time'],
                    'end_time'         => $data['end_time'],
                    'total_hours'      => $data['total_hours'],
                    'total_price'      => $data['total_price'],
                    'payment_method'   => $data['payment_method'],
                    'attendees_count'  => $data['attendees_count'] ?? null,
                    'setup_style'      => $data['setup_style'] ?? null,
                    'special_requests' => $finalRequests,
                    'status'           => 'confirmed',
                    'created_at'       => now(),
                    'updated_at'       => now(),
                ]);

                // អាចថែម Logic update status បន្ទប់ប្រជុំនៅទីនេះ (បើមាន)

                return $meetingBooking;
            }
        });
    }
}
