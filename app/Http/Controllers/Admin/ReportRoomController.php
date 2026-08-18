<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\HotelBooking;
use App\Models\Room;

class ReportRoomController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = HotelBooking::with(['user', 'room.roomType', 'details.roomType', 'payment']);

        // Filter by Date Range
        if ($request->filled('date_range')) {
            switch ($request->date_range) {
                case 'today':
                    $query->whereDate('created_at', today());
                    break;
                case 'yesterday':
                    $query->whereDate('created_at', today()->subDay());
                    break;
                case 'last_7_days':
                    $query->where('created_at', '>=', today()->subDays(7));
                    break;
                case 'this_month':
                    $query->whereMonth('created_at', now()->month)->whereYear('created_at', now()->year);
                    break;
            }
        }

        // Filter by Status
        if ($request->filled('status') && $request->status !== 'all') {
            $query->where('status', $request->status);
        }

        // Filter by Search (Booking Code, Customer Name, Customer Phone)
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('booking_code', 'like', "%{$search}%")
                    ->orWhere('customer_name', 'like', "%{$search}%")
                    ->orWhere('customer_phone', 'like', "%{$search}%")
                    ->orWhereHas('user', function ($uq) use ($search) {
                        $uq->where('name', 'like', "%{$search}%")
                            ->orWhere('phone', 'like', "%{$search}%");
                    });
            });
        }

        // ១. ទាញទិន្នន័យស្ថានភាពបន្ទប់បច្ចុប្បន្ន (Available, Booked, Maintenance)
        $roomStatus = Room::select('status', DB::raw('count(*) as total'))
            ->groupBy('status')
            ->get();

        $totalRoomsCount = Room::count();
        $bookedRoomsCount = Room::where('status', 'booked')->count();
        $occupancyRate = $totalRoomsCount > 0 ? round(($bookedRoomsCount / $totalRoomsCount) * 100, 1) : 0;

        // ២. ទាញប្រភេទបន្ទប់ដែលពេញនិយមបំផុតទាំង ៥
        $popularRooms = DB::table('hotel_booking_details')
            ->join('room_types', 'hotel_booking_details.room_type_id', '=', 'room_types.id')
            ->select('room_types.name', DB::raw('count(hotel_booking_details.id) as total_booked'))
            ->groupBy('room_types.id', 'room_types.name')
            ->orderBy('total_booked', 'desc')
            ->take(5)
            ->get();

        // ៣. ទាញទិន្នន័យសរុបនៃការកក់បែងចែកតាមស្ថានភាព
        $bookingStatus = HotelBooking::select('status', DB::raw('count(*) as total'))
            ->groupBy('status')
            ->get();

        // ៤. ទាញបញ្ជីនៃការកក់បន្ទប់ (Paginated 15/page)
        $perPage = $request->input('per_page', 10);
        $bookings = $query->orderBy('created_at', 'desc')->paginate($perPage)->withQueryString();

        $pendingCount = $bookingStatus->where('status', 'pending')->first()->total ?? 0;
        $confirmedCount = $bookingStatus->where('status', 'confirmed')->first()->total ?? 0;
        $completedCount = $bookingStatus->where('status', 'completed')->first()->total ?? 0;
        $cancelledCount = $bookingStatus->where('status', 'cancelled')->first()->total ?? 0;
        $totalBookingsCount = $pendingCount + $confirmedCount + $completedCount + $cancelledCount;

        $available = $roomStatus->where('status', 'available')->first()->total ?? 0;
        $booked = $roomStatus->where('status', 'booked')->first()->total ?? 0;
        $maintenance = $roomStatus->where('status', 'maintenance')->first()->total ?? 0;

        if ($request->ajax()) {
            return view('admin.reportrooms.partials.report_content', compact(
                'roomStatus',
                'popularRooms',
                'bookingStatus',
                'bookings',
                'occupancyRate',
                'totalRoomsCount',
                'bookedRoomsCount'
            ));
        }

        return view('admin.reportrooms.index', compact(
            'roomStatus',
            'popularRooms',
            'bookingStatus',
            'bookings',
            'occupancyRate',
            'totalRoomsCount',
            'bookedRoomsCount'
        ));
    }

    public function exportExcel(Request $request)
    {
        $query = HotelBooking::with(['user', 'room.roomType', 'payment']);

        if ($request->filled('date_range')) {
            switch ($request->date_range) {
                case 'today':
                    $query->whereDate('created_at', today());
                    break;
                case 'yesterday':
                    $query->whereDate('created_at', today()->subDay());
                    break;
                case 'last_7_days':
                    $query->where('created_at', '>=', today()->subDays(7));
                    break;
                case 'this_month':
                    $query->whereMonth('created_at', now()->month)->whereYear('created_at', now()->year);
                    break;
            }
        }

        if ($request->filled('status') && $request->status !== 'all') {
            $query->where('status', $request->status);
        }

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('booking_code', 'like', "%{$search}%")
                    ->orWhere('customer_name', 'like', "%{$search}%")
                    ->orWhere('customer_phone', 'like', "%{$search}%");
            });
        }

        $bookings = $query->orderBy('created_at', 'desc')->get();
        $fileName = 'Room_Booking_Report_' . date('Y-m-d_H-i') . '.csv';

        $headers = [
            "Content-type"        => "text/csv; charset=UTF-8",
            "Content-Disposition" => "attachment; filename=$fileName",
            "Pragma"              => "no-cache",
            "Cache-Control"       => "must-revalidate, post-check=0, pre-check=0",
            "Expires"             => "0"
        ];

        $columns = ['លេខកូដកក់', 'ឈ្មោះអតិថិជន', 'លេខទូរស័ព្ទ', 'លេខបន្ទប់', 'ប្រភេទបន្ទប់', 'ថ្ងៃចូល', 'ថ្ងៃចេញ', 'ចំនួនយប់', 'ប្រភពកក់', 'ស្ថានភាព', 'តម្លៃសរុប ($)'];

        $callback = function () use ($bookings, $columns) {
            $file = fopen('php://output', 'w');
            // Add UTF-8 BOM so Microsoft Excel opens Khmer text correctly!
            fprintf($file, chr(0xEF) . chr(0xBB) . chr(0xBF));
            fputcsv($file, $columns);

            foreach ($bookings as $b) {
                $nights = ($b->check_in && $b->check_out) ? \Carbon\Carbon::parse($b->check_in)->diffInDays(\Carbon\Carbon::parse($b->check_out)) : 1;
                $statusLabel = match ($b->status) {
                    'pending'   => 'រង់ចាំពិនិត្យ',
                    'confirmed' => 'បានបញ្ជាក់',
                    'completed' => 'បានបញ្ចប់',
                    'cancelled' => 'បានបោះបង់',
                    default     => $b->status
                };

                fputcsv($file, [
                    $b->booking_code,
                    $b->customer_name ?: ($b->user->name ?? 'ភ្ញៀវកក់ផ្ទាល់'),
                    $b->customer_phone ?: ($b->user->phone ?? 'N/A'),
                    $b->room->room_number ?? 'N/A',
                    $b->room->roomType->name ?? 'បន្ទប់ស្នាក់នៅ',
                    $b->check_in,
                    $b->check_out,
                    $nights,
                    $b->booking_type ?: 'Direct',
                    $statusLabel,
                    number_format($b->total_price, 2)
                ]);
            }

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }

    public function exportPdf(Request $request)
    {
        $query = HotelBooking::with(['user', 'room.roomType', 'payment']);

        if ($request->filled('date_range')) {
            switch ($request->date_range) {
                case 'today':
                    $query->whereDate('created_at', today());
                    break;
                case 'yesterday':
                    $query->whereDate('created_at', today()->subDay());
                    break;
                case 'last_7_days':
                    $query->where('created_at', '>=', today()->subDays(7));
                    break;
                case 'this_month':
                    $query->whereMonth('created_at', now()->month)->whereYear('created_at', now()->year);
                    break;
            }
        }

        if ($request->filled('status') && $request->status !== 'all') {
            $query->where('status', $request->status);
        }

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('booking_code', 'like', "%{$search}%")
                    ->orWhere('customer_name', 'like', "%{$search}%")
                    ->orWhere('customer_phone', 'like', "%{$search}%");
            });
        }

        $bookings = $query->orderBy('created_at', 'desc')->get();
        $totalRevenue = $bookings->where('status', '!=', 'cancelled')->sum('total_price');

        return view('admin.reportrooms.print', compact('bookings', 'totalRevenue'));
    }
}
