<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class CheckoutController extends Controller
{
    public function __construct()
    {
        // ធានាគ្រប់ Function ក្នុង Controller នេះ ត្រូវតែ Login ជាមុនសិន
        $this->middleware('auth');
    }


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

    public function process(Request $request)
    {
        // ១. Validate ទិន្នន័យពីអតិថិជន
        $request->validate([
            'first_name' => 'required',
            'last_name' => 'required',
            'phone' => 'required',
            'payment_method' => 'required'
        ]);

        $cart = session()->get('cart', []);
        if (empty($cart)) return response()->json(['status' => 'error', 'message' => 'កន្ត្រកទំនេរ!']);

        try {
            DB::beginTransaction();

            // ២. បង្កើត Booking Master record (ឧទាហរណ៍៖ ដាក់ចូលតារាង hotel_bookings)
            // ចំណាំ៖ ត្រង់នេះបងអាចកែសម្រួលទៅតាមរចនាសម្ព័ន្ធ Table ជាក់ស្តែងរបស់បង

            $bookingCode = 'PNT-' . strtoupper(Str::random(6)) . '-KH';

            foreach ($cart as $item) {
                if ($item['type'] == 'hotel') {
                    // បញ្ចូលក្នុងតារាងកក់សណ្ឋាគារ
                    DB::table('hotel_bookings')->insert([
                        'room_id' => $item['id'],
                        'check_in' => $item['check_in'],
                        'check_out' => $item['check_out'],
                        'total_price' => $item['total_price'],
                        'status' => 'pending',
                        'created_at' => now(),
                    ]);
                } else {
                    // បញ្ចូលក្នុងតារាងកក់សាលប្រជុំ
                    DB::table('meeting_bookings')->insert([
                        'meeting_room_id' => $item['id'],
                        'start_date' => $item['start_date'],
                        'end_date' => $item['end_date'],
                        'start_time' => $item['start_time'],
                        'end_time' => $item['end_time'],
                        'total_price' => $item['total_price'],
                        'status' => 'pending',
                        'created_at' => now(),
                    ]);
                }
            }

            DB::commit();

            // ៣. សម្អាត Cart ក្រោយកក់ជោគជ័យ
            session()->forget('cart');

            return response()->json([
                'status' => 'success',
                'message' => 'ការកក់របស់អ្នកត្រូវបានបញ្ជូនដោយជោគជ័យ!',
                'booking_code' => $bookingCode
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['status' => 'error', 'message' => 'មានបញ្ហាបច្ចេកទេស៖ ' . $e->getMessage()]);
        }
    }
}
