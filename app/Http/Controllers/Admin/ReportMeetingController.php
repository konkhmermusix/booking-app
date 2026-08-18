<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\MeetingBooking;

class ReportMeetingController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = MeetingBooking::with(['user', 'room', 'payment']);

        // Date Range Filter
        if ($request->filled('date_range')) {
            switch ($request->date_range) {
                case 'today':
                    $query->whereDate('start_date', today());
                    break;
                case 'yesterday':
                    $query->whereDate('start_date', today()->subDay());
                    break;
                case 'last_7_days':
                    $query->where('start_date', '>=', today()->subDays(7));
                    break;
                case 'this_month':
                    $query->whereMonth('start_date', now()->month)->whereYear('start_date', now()->year);
                    break;
            }
        }

        // Status Filter
        if ($request->filled('status') && $request->status !== 'all') {
            $query->where('status', $request->status);
        }

        // Search Filter
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('booking_code', 'like', "%{$search}%")
                    ->orWhere('customer_name', 'like', "%{$search}%")
                    ->orWhere('customer_phone', 'like', "%{$search}%")
                    ->orWhereHas('user', function ($uq) use ($search) {
                        $uq->where('name', 'like', "%{$search}%");
                    });
            });
        }

        // 1. Overall Stats
        $meetingStats = MeetingBooking::select(
            DB::raw('count(*) as total_meetings'),
            DB::raw('COALESCE(SUM(total_price), 0) as total_revenue'),
            DB::raw('COALESCE(SUM(attendees_count), 0) as total_attendees')
        )->first();

        // Peak Booking Day Analysis
        $peakDay = MeetingBooking::select(DB::raw('DAYNAME(start_date) as day_name'), DB::raw('count(*) as total'))
            ->groupBy('day_name')
            ->orderBy('total', 'desc')
            ->first();
        $peakDayName = $peakDay ? $peakDay->day_name : 'ច័ន្ទ - សុក្រ';

        // 2. Paginated List
        $perPage = $request->input('per_page', 10);
        $meetingBookings = $query->orderBy('created_at', 'desc')->paginate($perPage)->withQueryString();

        // 3. Calendar View Events Data
        $calendarEvents = MeetingBooking::with('room')->get()->map(function ($mb) {
            return [
                'id'          => $mb->id,
                'title'       => ($mb->room->room_number ?? 'សាលប្រជុំ') . ' - ' . ($mb->customer_name ?: ($mb->user->name ?? 'ភ្ញៀវ')),
                'start'       => $mb->start_date ? $mb->start_date->format('Y-m-d') : null,
                'end'         => $mb->end_date ? $mb->end_date->format('Y-m-d') : null,
                'status'      => $mb->status,
                'total_price' => $mb->total_price,
            ];
        });

        if ($request->ajax()) {
            return view('admin.reportmeetings.partials.report_content', compact(
                'meetingStats',
                'meetingBookings',
                'peakDayName',
                'calendarEvents'
            ));
        }

        return view('admin.reportmeetings.index', compact(
            'meetingStats',
            'meetingBookings',
            'peakDayName',
            'calendarEvents'
        ));
    }

    public function exportExcel(Request $request)
    {
        $query = MeetingBooking::with(['user', 'room', 'payment']);

        if ($request->filled('date_range')) {
            switch ($request->date_range) {
                case 'today':
                    $query->whereDate('start_date', today());
                    break;
                case 'yesterday':
                    $query->whereDate('start_date', today()->subDay());
                    break;
                case 'last_7_days':
                    $query->where('start_date', '>=', today()->subDays(7));
                    break;
                case 'this_month':
                    $query->whereMonth('start_date', now()->month)->whereYear('start_date', now()->year);
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
        $fileName = 'Meeting_Booking_Report_' . date('Y-m-d_H-i') . '.csv';

        $headers = [
            "Content-type"        => "text/csv; charset=UTF-8",
            "Content-Disposition" => "attachment; filename=$fileName",
            "Pragma"              => "no-cache",
            "Cache-Control"       => "must-revalidate, post-check=0, pre-check=0",
            "Expires"             => "0"
        ];

        $columns = ['លេខកូដកក់', 'ឈ្មោះអតិថិជន/អង្គភាព', 'លេខទូរស័ព្ទ', 'សាលប្រជុំ', 'ថ្ងៃប្រជុំ', 'ម៉ោង', 'អ្នកចូលរួម', 'ស្ថានភាព', 'តម្លៃសរុប ($)'];

        $callback = function () use ($bookings, $columns) {
            $file = fopen('php://output', 'w');
            fprintf($file, chr(0xEF) . chr(0xBB) . chr(0xBF)); // UTF-8 BOM for Khmer text in Excel
            fputcsv($file, $columns);

            foreach ($bookings as $b) {
                $statusLabel = match ($b->status) {
                    'pending'   => 'រង់ចាំពិនិត្យ',
                    'confirmed' => 'បានបញ្ជាក់',
                    'completed' => 'បានបញ្ចប់',
                    'cancelled' => 'បានបោះបង់',
                    default     => $b->status
                };

                $dateDisplay = ($b->start_date ? $b->start_date->format('d/m/Y') : 'N/A') . ' - ' . ($b->end_date ? $b->end_date->format('d/m/Y') : 'N/A');
                $timeDisplay = ($b->start_time ? date('H:i', strtotime($b->start_time)) : '07:00') . ' - ' . ($b->end_time ? date('H:i', strtotime($b->end_time)) : '17:00');

                fputcsv($file, [
                    $b->booking_code,
                    $b->customer_name ?: ($b->user->name ?? 'ភ្ញៀវកក់ផ្ទាល់'),
                    $b->customer_phone ?: ($b->user->phone ?? 'N/A'),
                    $b->room->room_number ?? 'សាលប្រជុំ',
                    $dateDisplay,
                    $timeDisplay,
                    $b->attendees_count ?: 0,
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
        $query = MeetingBooking::with(['user', 'room', 'payment']);

        if ($request->filled('date_range')) {
            switch ($request->date_range) {
                case 'today':
                    $query->whereDate('start_date', today());
                    break;
                case 'yesterday':
                    $query->whereDate('start_date', today()->subDay());
                    break;
                case 'last_7_days':
                    $query->where('start_date', '>=', today()->subDays(7));
                    break;
                case 'this_month':
                    $query->whereMonth('start_date', now()->month)->whereYear('start_date', now()->year);
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

        return view('admin.reportmeetings.print', compact('bookings', 'totalRevenue'));
    }
}
