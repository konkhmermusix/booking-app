<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use App\Models\Hotel;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Barryvdh\DomPDF\Facade\Pdf;


class BookingController extends Controller
{
    public function index(Request $request)
    {
        $query = Booking::with(['user', 'hotel']);

        // Filter តាម Booking Code ឬ ឈ្មោះសណ្ឋាគារ
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where('booking_code', 'like', "%{$search}%")
                ->orWhereHas('hotel', fn($q) => $q->where('name', 'like', "%{$search}%"));
        }

        // Filter តាមស្ថានភាព
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $bookings = $query->latest()->paginate(15)->withQueryString();
        $hotels = Hotel::all();
        $users = User::where('role', 'customer')->get();

        return view('admin.bookings.index', compact('bookings', 'hotels', 'users'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'hotel_id' => 'required|exists:hotels,id',
            'user_id' => 'nullable|exists:users,id',
            'check_in' => 'required|date|after_or_equal:today',
            'check_out' => 'required|date|after:check_in',
            'total_price' => 'required|numeric|min:0',
            'status' => 'required|in:pending,confirmed,cancelled,completed',
        ]);

        $booking = new Booking($request->all());
        // បង្កើតលេខកូដកក់ ឧទាហរណ៍៖ BK-168A2B
        $booking->booking_code = 'BK-' . strtoupper(Str::random(6));
        $booking->save();

        return back()->with('success', 'ការកក់លេខ ' . $booking->booking_code . ' ត្រូវបានបង្កើត!');
    }

    public function update(Request $request, Booking $booking)
    {
        $request->validate([
            'status' => 'required|in:pending,confirmed,cancelled,completed',
            'check_in' => 'required|date',
            'check_out' => 'required|date|after:check_in',
        ]);

        $booking->update($request->all());
        return back()->with('success', 'បច្ចុប្បន្នភាពការកក់ជោគជ័យ!');
    }

    public function destroy(Booking $booking)
    {
        // ក្នុង Production គេមិនសូវលុប Booking ទេ តែបើចង់លុប៖
        $booking->delete();
        return back()->with('success', 'លុបទិន្នន័យការកក់រួចរាល់!');
    }



    public function downloadInvoice(Booking $booking)
    {
        // Eager load relationships ដើម្បីល្បឿន
        $booking->load(['user', 'hotel']);

        // បង្កើត PDF ចេញពីឯកសារ Blade
        $pdf = Pdf::loadView('admin.bookings.invoice_pdf', compact('booking'));

        // កំណត់ឈ្មោះ File ពេលទាញយក
        return $pdf->download('Invoice-' . $booking->booking_code . '.pdf');
    }

    // public function downloadInvoice(Booking $booking)
    // {
    //     $booking->load(['user', 'hotel']);

    //     $pdf = Pdf::loadView('admin.bookings.invoice_pdf', compact('booking'))
    //         ->setPaper('a4', 'portrait')
    //         ->setOptions([
    //             'tempDir' => public_path(),
    //             'chroot'  => public_path(),
    //             'isRemoteEnabled' => true,
    //             'isFontSubsettingEnabled' => true,
    //         ]);

    //     return $pdf->stream('Invoice-' . $booking->booking_code . '.pdf');
    // }
}
