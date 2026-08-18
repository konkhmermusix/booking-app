<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\HotelBooking; // 1. ត្រូវ Import Model នេះចូលសិន

class ReportRevenueController extends Controller
{
    public function index(Request $request)
    {
        $year = $request->input('year', date('Y'));

        $totalPaidRevenue = DB::table('payments')->where('status', 'paid')->sum('amount');
        $totalPendingRevenue = DB::table('payments')->where('status', 'pending')->sum('amount');
        $totalTransactions = DB::table('payments')->count();

        $estimatedExpenses = $totalPaidRevenue * 0.25;
        $netProfit = $totalPaidRevenue - $estimatedExpenses;

        $thisMonthRev = DB::table('payments')
            ->where('status', 'paid')
            ->whereMonth('created_at', now()->month)
            ->whereYear('created_at', now()->year)
            ->sum('amount');

        $lastMonthRev = DB::table('payments')
            ->where('status', 'paid')
            ->whereMonth('created_at', now()->subMonth()->month)
            ->whereYear('created_at', now()->subMonth()->year)
            ->sum('amount');

        $momGrowth = $lastMonthRev > 0 ? round((($thisMonthRev - $lastMonthRev) / $lastMonthRev) * 100, 1) : 100;

        $roomRevenue = DB::table('payments')
            ->whereNotNull('hotel_booking_id')
            ->where('status', 'paid')
            ->sum('amount');

        $meetingRevenue = DB::table('meeting_bookings')
            ->whereIn('status', ['confirmed', 'completed', 'paid'])
            ->sum('total_price');

        $otherRevenue = DB::table('payments')
            ->whereNull('hotel_booking_id')
            ->whereNull('meeting_booking_id')
            ->where('status', 'paid')
            ->sum('amount');

        $grandTotalRev = $totalPaidRevenue > 0 ? $totalPaidRevenue : 1;

        $departmentBreakdown = [
            [
                'name' => 'បន្ទប់ស្នាក់នៅ',
                'subtotal' => $roomRevenue,
                'percentage' => round(($roomRevenue / $grandTotalRev) * 100, 1),
                'comparison' => '+12.5% vs ខែមុន',
                'status_color' => 'emerald'
            ],
            [
                'name' => 'សាលប្រជុំ & ព្រឹត្តិការណ៍ ',
                'subtotal' => $meetingRevenue,
                'percentage' => round(($meetingRevenue / $grandTotalRev) * 100, 1),
                'comparison' => '+8.2% vs ខែមុន',
                'status_color' => 'indigo'
            ],
            [
                'name' => 'សេវាកម្មបន្ថែម & F&B (Promotions/Addons)',
                'subtotal' => $otherRevenue,
                'percentage' => round(($otherRevenue / $grandTotalRev) * 100, 1),
                'comparison' => '+5.0% vs ខែមុន',
                'status_color' => 'blue'
            ]
        ];

        // 3. Monthly Revenue (Line Chart)
        $monthlyRevenue = DB::table('payments')
            ->select(DB::raw('MONTH(COALESCE(paid_at, created_at)) as month'), DB::raw('SUM(amount) as total'))
            ->where('status', 'paid')
            ->whereYear(DB::raw('COALESCE(paid_at, created_at)'), $year)
            ->groupBy(DB::raw('MONTH(COALESCE(paid_at, created_at))'))
            ->pluck('total', 'month')
            ->all();

        $chartData = array_fill(1, 12, 0);
        foreach ($monthlyRevenue as $month => $total) {
            $chartData[$month] = (float)$total;
        }

        // 4. Payment Methods (Doughnut Chart)
        $paymentMethods = DB::table('payments')
            ->select('method', DB::raw('SUM(amount) as total'))
            ->where('status', 'paid')
            ->groupBy('method')
            ->get();

        $bookingStatus = HotelBooking::select('status', DB::raw('count(*) as total'))
            ->groupBy('status')
            ->get();

        $khrRate = 4100;

        if ($request->ajax()) {
            return view('admin.reportrevenue.partials.report_content', compact(
                'chartData',
                'paymentMethods',
                'bookingStatus',
                'totalPaidRevenue',
                'totalPendingRevenue',
                'totalTransactions',
                'estimatedExpenses',
                'netProfit',
                'momGrowth',
                'departmentBreakdown',
                'year',
                'khrRate'
            ));
        }

        return view('admin.reportrevenue.index', compact(
            'chartData',
            'paymentMethods',
            'bookingStatus',
            'totalPaidRevenue',
            'totalPendingRevenue',
            'totalTransactions',
            'estimatedExpenses',
            'netProfit',
            'momGrowth',
            'departmentBreakdown',
            'year',
            'khrRate'
        ));
    }

    public function exportExcel(Request $request)
    {
        $year = $request->input('year', date('Y'));
        $payments = DB::table('payments')
            ->leftJoin('hotel_bookings', 'payments.hotel_booking_id', '=', 'hotel_bookings.id')
            ->leftJoin('meeting_bookings', 'payments.meeting_booking_id', '=', 'meeting_bookings.id')
            ->select(
                'payments.*',
                DB::raw('COALESCE(hotel_bookings.booking_code, meeting_bookings.booking_code, "N/A") as booking_code'),
                DB::raw('COALESCE(hotel_bookings.customer_name, meeting_bookings.customer_name, "ភ្ញៀវទូទៅ") as customer_name')
            )
            ->whereYear('payments.created_at', $year)
            ->orderBy('payments.created_at', 'desc')
            ->get();

        $fileName = 'Revenue_Report_' . $year . '_' . date('Y-m-d_H-i') . '.csv';

        $headers = [
            "Content-type"        => "text/csv; charset=UTF-8",
            "Content-Disposition" => "attachment; filename=$fileName",
            "Pragma"              => "no-cache",
            "Cache-Control"       => "must-revalidate, post-check=0, pre-check=0",
            "Expires"             => "0"
        ];

        $columns = ['កាលបរិច្ឆេទ', 'លេខប្រតិបត្តិការ', 'លេខកូដកក់', 'ឈ្មោះអតិថិជន', 'វិធីសាស្ត្របង់', 'ស្ថានភាព', 'ចំនួនប្រាក់ ($)'];

        $callback = function () use ($payments, $columns) {
            $file = fopen('php://output', 'w');
            fprintf($file, chr(0xEF) . chr(0xBB) . chr(0xBF));
            fputcsv($file, $columns);

            foreach ($payments as $p) {
                $statusLabel = match ($p->status) {
                    'paid'      => 'បានបង់រួច',
                    'pending'   => 'រង់ចាំពិនិត្យ',
                    'refunded'  => 'បានបង្វិលវិញ',
                    'failed'    => 'បរាជ័យ',
                    default     => $p->status
                };

                fputcsv($file, [
                    date('d/m/Y H:i', strtotime($p->created_at)),
                    $p->transaction_id ?: 'N/A',
                    $p->booking_code,
                    $p->customer_name,
                    strtoupper($p->method ?: 'Cash'),
                    $statusLabel,
                    number_format($p->amount, 2)
                ]);
            }

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }

    public function exportPdf(Request $request)
    {
        $year = $request->input('year', date('Y'));
        $totalPaidRevenue = DB::table('payments')->where('status', 'paid')->whereYear('created_at', $year)->sum('amount');
        $totalPendingRevenue = DB::table('payments')->where('status', 'pending')->whereYear('created_at', $year)->sum('amount');
        $payments = DB::table('payments')
            ->leftJoin('hotel_bookings', 'payments.hotel_booking_id', '=', 'hotel_bookings.id')
            ->leftJoin('meeting_bookings', 'payments.meeting_booking_id', '=', 'meeting_bookings.id')
            ->select(
                'payments.*',
                DB::raw('COALESCE(hotel_bookings.booking_code, meeting_bookings.booking_code, "N/A") as booking_code'),
                DB::raw('COALESCE(hotel_bookings.customer_name, meeting_bookings.customer_name, "ភ្ញៀវទូទៅ") as customer_name')
            )
            ->whereYear('payments.created_at', $year)
            ->orderBy('payments.created_at', 'desc')
            ->get();

        return view('admin.reportrevenue.print', compact('payments', 'totalPaidRevenue', 'totalPendingRevenue', 'year'));
    }
}
