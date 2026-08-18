<?php

namespace App\Services;

use App\Repositories\RoomBookingRepository;
use App\Repositories\RoomRepository;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use App\Models\Room;
use App\Models\Payment;

class RoomBookingService
{
    protected RoomBookingRepository $roomBookingRepo;
    protected RoomRepository $roomRepo;

    public function __construct(
        RoomBookingRepository $roomBookingRepo,
        RoomRepository $roomRepo
    ) {
        $this->roomBookingRepo = $roomBookingRepo;
        $this->roomRepo = $roomRepo;
    }

    public function getAllBookings(array $filters = [])
    {
        return $this->roomBookingRepo->getAllBookings($filters);
    }

    public function createBooking(array $data)
    {
        return DB::transaction(function () use ($data) {

            $walkInInfo = "【ភ្ញៀវមកផ្ទាល់】 ឈ្មោះ: " . $data['customer_name'] . " | លេខទូរស័ព្ទ: " . $data['customer_phone'];
            $finalRequests = !empty($data['special_requests'])
                ? $walkInInfo . " | មតិផ្សេងៗ: " . $data['special_requests']
                : $walkInInfo;

            $bookingCode = $this->roomBookingRepo->generateBookingCode();

            $roomIds = !empty($data['room_ids']) ? array_filter((array)$data['room_ids']) : (!empty($data['room_id']) ? [$data['room_id']] : []);
            if (empty($roomIds)) {
                throw new \Exception("សូមជ្រើសរើសយ៉ាងហោចណាស់បន្ទប់មួយ!");
            }
            $primaryRoomId = $roomIds[0];

            $booking = $this->roomBookingRepo->create([
                'booking_code'     => $bookingCode,
                'booking_type'     => 'walk_in',
                'hotel_id'         => $data['hotel_id'] ?? 1,
                'user_id'          => auth()->id(),
                'customer_name'    => $data['customer_name'] ?? null,
                'customer_phone'   => $data['customer_phone'] ?? null,
                'customer_email'   => $data['customer_email'] ?? null,
                'room_id'          => $primaryRoomId,
                'check_in'         => $data['check_in'],
                'check_out'        => $data['check_out'],
                'check_in_time'    => '14:00:00',
                'check_out_time'   => '12:00:00',
                'total_price'      => $data['total_price'],
                'payment_method'   => $data['payment_method'],
                'special_requests' => $finalRequests,
                'status'           => 'confirmed',
            ]);

            foreach ($roomIds as $rId) {
                $room = Room::with('roomType')->find($rId);
                if ($room) {
                    $pricePerNight = $room->roomType->base_price ?? 0;
                    $booking->details()->create([
                        'room_id'          => $room->id,
                        'room_type_id'     => $room->room_type_id,
                        'price_at_booking' => $pricePerNight,
                    ]);
                    $this->roomRepo->updateStatus($room->id, 'booked');
                }
            }

            // បង្កើតព័ត៌មាននៃការទូទាត់ប្រាក់
            $payStatus = $data['payment_status'] ?? 'paid';
            $payMethod = in_array($data['payment_method'] ?? 'cash', ['qr', 'bank_transfer', 'khqr']) ? 'qr' : 'cash';

            Payment::create([
                'hotel_booking_id' => $booking->id,
                'method'           => $payMethod,
                'amount'           => $data['total_price'],
                'currency'         => 'USD',
                'transaction_id'   => $data['transaction_id'] ?? null,
                'status'           => $payStatus,
                'paid_at'          => $payStatus === 'paid' ? now() : null,
            ]);

            return $booking;
        });
    }
}
