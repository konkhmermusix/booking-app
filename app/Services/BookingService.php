<?php

namespace App\Services;

use App\Repositories\BookingRepository;
use App\Repositories\RoomRepository;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use App\Models\Room;
use App\Models\MeetingBooking;

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
                ? $walkInInfo . " | មតិផ្សេងៗ: " . $data['special_requests']
                : $walkInInfo;

            $bookingCode = 'PNT-' . strtoupper(Str::random(6));

            // កាត់ខណ្ឌចែកទៅតាមប្រភេទនៃការកក់
            if ($data['booking_category'] === 'hotel') {

                // បញ្ចូលទៅតារាងទី១ (hotel_bookings)
                $booking = $this->bookingRepo->create([
                    'booking_code'     => $bookingCode,
                    'hotel_id'         => $data['hotel_id'],
                    'user_id'          => null,
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

                // បញ្ចូលទៅតារាងទី២ (hotel_booking_details)
                $room = Room::findOrFail($data['room_id']);
                $booking->details()->create([
                    'room_id'          => $room->id,
                    'room_type_id'     => $room->room_type_id,
                    'price_at_booking' => $data['total_price'],
                ]);

                // បច្ចុប្បន្នភាពស្ថានភាពបន្ទប់
                $this->roomRepo->updateStatus($data['room_id'], 'booked');

                return $booking;
            } else if ($data['booking_category'] === 'meeting_room') {

                // ករណីកក់បន្ទប់ប្រជុំ — ប្រើ MeetingBooking Model + meeting_bookings table
                // user_id ប្រើ ID របស់ Admin ដែល Login (Walk-in ក្នុងនាម Admin)
                $meetingBooking = MeetingBooking::create([
                    'booking_code'     => $bookingCode,
                    'user_id'          => auth()->id(),
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
                ]);

                // ប្តូរស្ថានភាពបន្ទប់ប្រជុំទៅជា booked
                $this->roomRepo->updateStatus($data['meeting_room_id'], 'booked');

                return $meetingBooking;
            }
        });
    }
}
