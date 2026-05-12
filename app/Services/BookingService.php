<?php

namespace App\Services;

use App\Repositories\BookingRepository;
use App\Repositories\RoomRepository;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use App\Models\Room;

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

            // Create Booking
            $booking = $this->bookingRepo->create([
                // 'user_id'          => auth()->id(),
                'hotel_id'         => $data['hotel_id'],
                'room_id'          => $data['room_id'],
                'check_in'         => $data['check_in'],
                'check_out'        => $data['check_out'],
                'total_price'      => $data['total_price'],
                'status'           => 'confirmed',
                'booking_code'     => 'PNT-' . strtoupper(Str::random(6)),
                'check_in_time'    => '14:00:00',
                'check_out_time'   => '12:00:00',
                'special_requests' => $data['special_requests'] ?? null,
            ]);

            // Room info
            $room = Room::findOrFail($data['room_id']);

            $booking->details()->create([
                'room_id'          => $room->id,
                'room_type_id'     => $room->room_type_id,
                'price_at_booking' => $data['total_price'],
            ]);

            // Payment
            $booking->payment()->create([
                'method'   => $data['payment_method'],
                'amount'   => $data['total_price'],
                'currency' => 'USD',
                'status'   => 'paid',
                'paid_at'  => now(),
            ]);

            // Update Room Status
            $this->roomRepo->updateStatus($data['room_id'], 'booked');

            return $booking;
        });
    }

    public function updateBookingStatus($id, $status)
    {
        return DB::transaction(function () use ($id, $status) {

            $booking = $this->bookingRepo->find($id);

            if (in_array($status, ['cancelled', 'completed'])) {
                $this->roomRepo->updateStatus($booking->room_id, 'available');
            }

            return $this->bookingRepo->update($id, [
                'status' => $status
            ]);
        });
    }
}
