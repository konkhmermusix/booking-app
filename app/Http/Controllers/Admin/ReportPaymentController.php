<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ReportPaymentController extends Controller
{
    public function index(Request $request)
    {
        $query = DB::table('payments')
            ->leftJoin('hotel_bookings', 'payments.hotel_booking_id', '=', 'hotel_bookings.id')
            ->leftJoin('meeting_bookings', 'payments.meeting_booking_id', '=', 'meeting_bookings.id')
            ->leftJoin('users as hotel_users', 'hotel_bookings.user_id', '=', 'hotel_users.id')
            ->leftJoin('users as meeting_users', 'meeting_bookings.user_id', '=', 'meeting_users.id')
            ->select(
                'payments.*',
                DB::raw('COALESCE(hotel_bookings.booking_code, meeting_bookings.booking_code, "N/A") as booking_code'),
                DB::raw('COALESCE(hotel_bookings.customer_name, hotel_users.name, meeting_bookings.customer_name, meeting_users.name, "ភ្ញៀវទូទៅ") as customer_name')
            );

        // Date Range Filter
        if ($request->filled('date_range')) {
            switch ($request->date_range) {
                case 'today':
                    $query->whereDate('payments.created_at', today());
                    break;
                case 'yesterday':
                    $query->whereDate('payments.created_at', today()->subDay());
                    break;
                case 'last_7_days':
                    $query->where('payments.created_at', '>=', today()->subDays(7));
                    break;
                case 'this_month':
                    $query->whereMonth('payments.created_at', now()->month)->whereYear('payments.created_at', now()->year);
                    break;
            }
        }

        // Status Filter
        if ($request->filled('status') && $request->status !== 'all') {
            $query->where('payments.status', $request->status);
        }

        // Method Filter
        if ($request->filled('method') && $request->method !== 'all') {
            $query->where('payments.method', $request->method);
        }

        // Search Filter
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('payments.transaction_id', 'like', "%{$search}%")
                  ->orWhere('hotel_bookings.booking_code', 'like', "%{$search}%")
                  ->orWhere('meeting_bookings.booking_code', 'like', "%{$search}%")
                  ->orWhere('hotel_bookings.customer_name', 'like', "%{$search}%")
                  ->orWhere('meeting_bookings.customer_name', 'like', "%{$search}%");
            });
        }

        // Summary KPI Metrics
        $paidAmountCollected = DB::table('payments')->where('status', 'paid')->sum('amount');
        $pendingAmount = DB::table('payments')->where('status', 'pending')->sum('amount');
        $totalDeposits = DB::table('payments')->where('status', 'refunded')->sum('amount');
        $totalCount = DB::table('payments')->count();

        $perPage = $request->input('per_page', 10);
        $payments = $query->orderBy('payments.created_at', 'desc')->paginate($perPage)->appends($request->query());

        $khrRate = 4100;

        if ($request->ajax()) {
            return view('admin.reportpayments.partials.report_content', compact(
                'payments',
                'paidAmountCollected',
                'pendingAmount',
                'totalDeposits',
                'totalCount',
                'khrRate'
            ));
        }

        return view('admin.reportpayments.index', compact(
            'payments',
            'paidAmountCollected',
            'pendingAmount',
            'totalDeposits',
            'totalCount',
            'khrRate'
        ));
    }

    public function exportExcel(Request $request)
    {
        $query = DB::table('payments')
            ->leftJoin('hotel_bookings', 'payments.hotel_booking_id', '=', 'hotel_bookings.id')
            ->leftJoin('meeting_bookings', 'payments.meeting_booking_id', '=', 'meeting_bookings.id')
            ->leftJoin('users as hotel_users', 'hotel_bookings.user_id', '=', 'hotel_users.id')
            ->leftJoin('users as meeting_users', 'meeting_bookings.user_id', '=', 'meeting_users.id')
            ->select(
                'payments.*',
                DB::raw('COALESCE(hotel_bookings.booking_code, meeting_bookings.booking_code, "N/A") as booking_code'),
                DB::raw('COALESCE(hotel_bookings.customer_name, hotel_users.name, meeting_bookings.customer_name, meeting_users.name, "ភ្ញៀវទូទៅ") as customer_name')
            );

        if ($request->filled('date_range')) {
            switch ($request->date_range) {
                case 'today':
                    $query->whereDate('payments.created_at', today());
                    break;
                case 'yesterday':
                    $query->whereDate('payments.created_at', today()->subDay());
                    break;
                case 'last_7_days':
                    $query->where('payments.created_at', '>=', today()->subDays(7));
                    break;
                case 'this_month':
                    $query->whereMonth('payments.created_at', now()->month)->whereYear('payments.created_at', now()->year);
                    break;
            }
        }

        if ($request->filled('status') && $request->status !== 'all') {
            $query->where('payments.status', $request->status);
        }

        if ($request->filled('method') && $request->method !== 'all') {
            $query->where('payments.method', $request->method);
        }

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('payments.transaction_id', 'like', "%{$search}%")
                  ->orWhere('hotel_bookings.booking_code', 'like', "%{$search}%")
                  ->orWhere('meeting_bookings.booking_code', 'like', "%{$search}%");
            });
        }

        $payments = $query->orderBy('payments.created_at', 'desc')->get();
        $fileName = 'Payment_Transactions_Report_' . date('Y-m-d_H-i') . '.csv';

        $headers = [
            "Content-type"        => "text/csv; charset=UTF-8",
            "Content-Disposition" => "attachment; filename=$fileName",
            "Pragma"              => "no-cache",
            "Cache-Control"       => "must-revalidate, post-check=0, pre-check=0",
            "Expires"             => "0"
        ];

        $columns = ['កាលបរិច្ឆេទ', 'លេខប្រតិបត្តិការ', 'លេខកូដកក់', 'ឈ្មោះអតិថិជន', 'វិធីសាស្ត្របង់', 'ស្ថានភាព', 'ចំនួនប្រាក់ ($)'];

        $callback = function() use ($payments, $columns) {
            $file = fopen('php://output', 'w');
            fprintf($file, chr(0xEF).chr(0xBB).chr(0xBF));
            fputcsv($file, $columns);

            foreach ($payments as $p) {
                $statusLabel = match($p->status) {
                    'paid'      => 'បានបង់រួច',
                    'pending'   => 'រង់ចាំពិនិត្យ',
                    'refunded'  => 'បានបង្វិល',
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
        $query = DB::table('payments')
            ->leftJoin('hotel_bookings', 'payments.hotel_booking_id', '=', 'hotel_bookings.id')
            ->leftJoin('meeting_bookings', 'payments.meeting_booking_id', '=', 'meeting_bookings.id')
            ->leftJoin('users as hotel_users', 'hotel_bookings.user_id', '=', 'hotel_users.id')
            ->leftJoin('users as meeting_users', 'meeting_bookings.user_id', '=', 'meeting_users.id')
            ->select(
                'payments.*',
                DB::raw('COALESCE(hotel_bookings.booking_code, meeting_bookings.booking_code, "N/A") as booking_code'),
                DB::raw('COALESCE(hotel_bookings.customer_name, hotel_users.name, meeting_bookings.customer_name, meeting_users.name, "ភ្ញៀវទូទៅ") as customer_name')
            );

        if ($request->filled('date_range')) {
            switch ($request->date_range) {
                case 'today':
                    $query->whereDate('payments.created_at', today());
                    break;
                case 'yesterday':
                    $query->whereDate('payments.created_at', today()->subDay());
                    break;
                case 'last_7_days':
                    $query->where('payments.created_at', '>=', today()->subDays(7));
                    break;
                case 'this_month':
                    $query->whereMonth('payments.created_at', now()->month)->whereYear('payments.created_at', now()->year);
                    break;
            }
        }

        if ($request->filled('status') && $request->status !== 'all') {
            $query->where('payments.status', $request->status);
        }

        if ($request->filled('method') && $request->method !== 'all') {
            $query->where('payments.method', $request->method);
        }

        $payments = $query->orderBy('payments.created_at', 'desc')->get();
        $totalPaidAmount = $payments->where('status', 'paid')->sum('amount');

        return view('admin.reportpayments.print', compact('payments', 'totalPaidAmount'));
    }
}
