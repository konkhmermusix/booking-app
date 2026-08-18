<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ReportCustomerController extends Controller
{
    public function index(Request $request)
    {
        $query = DB::table('hotel_bookings')
            ->leftJoin('users', 'hotel_bookings.user_id', '=', 'users.id')
            ->select(
                DB::raw('MIN(hotel_bookings.id) as id'),
                DB::raw('COALESCE(users.name, hotel_bookings.customer_name, "ភ្ញៀវអនឡាញ") as name'),
                DB::raw('COALESCE(users.email, hotel_bookings.customer_email, "គ្មានអ៊ីមែល") as email'),
                DB::raw('COALESCE(users.phone, hotel_bookings.customer_phone, "គ្មានលេខទូរស័ព្ទ") as phone'),
                DB::raw('COUNT(hotel_bookings.id) as total_bookings'),
                DB::raw('COALESCE(SUM(hotel_bookings.total_price), 0) as lifetime_spend'),
                DB::raw('MAX(hotel_bookings.created_at) as last_visit')
            )
            ->groupBy(
                DB::raw('COALESCE(users.name, hotel_bookings.customer_name, "ភ្ញៀវអនឡាញ")'),
                DB::raw('COALESCE(users.email, hotel_bookings.customer_email, "គ្មានអ៊ីមែល")'),
                DB::raw('COALESCE(users.phone, hotel_bookings.customer_phone, "គ្មានលេខទូរស័ព្ទ")')
            );

        // Search Filter
        if ($request->filled('search')) {
            $search = $request->search;
            $query->havingRaw('name LIKE ? OR email LIKE ? OR phone LIKE ?', ["%{$search}%", "%{$search}%", "%{$search}%"]);
        }

        // Segment Filter
        if ($request->filled('segment') && $request->segment !== 'all') {
            if ($request->segment === 'vip') {
                $query->havingRaw('COUNT(hotel_bookings.id) >= 5');
            } elseif ($request->segment === 'regular') {
                $query->havingRaw('COUNT(hotel_bookings.id) BETWEEN 2 AND 4');
            } elseif ($request->segment === 'new') {
                $query->havingRaw('COUNT(hotel_bookings.id) = 1');
            }
        }

        // 1. Customer KPI Stats
        $totalUniqueCustomers = DB::table('hotel_bookings')
            ->count(DB::raw('DISTINCT COALESCE(user_id, customer_email, customer_phone)'));

        $newCustomersThisMonth = DB::table('hotel_bookings')
            ->whereMonth('created_at', now()->month)
            ->whereYear('created_at', now()->year)
            ->count(DB::raw('DISTINCT COALESCE(user_id, customer_email, customer_phone)'));

        $returningCustomersCount = DB::table('hotel_bookings')
            ->select('user_id', DB::raw('count(*) as total'))
            ->groupBy('user_id')
            ->havingRaw('total > 1')
            ->get()
            ->count();

        $perPage = $request->input('per_page', 10);
        $topCustomers = $query->orderBy('total_bookings', 'desc')->paginate($perPage)->appends($request->query());

        // Attach past history items for history timeline modal
        $allBookingsMap = DB::table('hotel_bookings')
            ->select('customer_name', 'booking_code', 'check_in', 'check_out', 'total_price', 'status')
            ->orderBy('created_at', 'desc')
            ->get()
            ->groupBy('customer_name');

        if ($request->ajax()) {
            return view('admin.reportcustomers.partials.report_content', compact(
                'topCustomers',
                'totalUniqueCustomers',
                'newCustomersThisMonth',
                'returningCustomersCount',
                'allBookingsMap'
            ));
        }

        return view('admin.reportcustomers.index', compact(
            'topCustomers',
            'totalUniqueCustomers',
            'newCustomersThisMonth',
            'returningCustomersCount',
            'allBookingsMap'
        ));
    }

    public function exportExcel(Request $request)
    {
        $query = DB::table('hotel_bookings')
            ->leftJoin('users', 'hotel_bookings.user_id', '=', 'users.id')
            ->select(
                DB::raw('COALESCE(users.name, hotel_bookings.customer_name, "ភ្ញៀវអនឡាញ") as name'),
                DB::raw('COALESCE(users.email, hotel_bookings.customer_email, "គ្មានអ៊ីមែល") as email'),
                DB::raw('COALESCE(users.phone, hotel_bookings.customer_phone, "គ្មានលេខទូរស័ព្ទ") as phone'),
                DB::raw('COUNT(hotel_bookings.id) as total_bookings'),
                DB::raw('COALESCE(SUM(hotel_bookings.total_price), 0) as lifetime_spend'),
                DB::raw('MAX(hotel_bookings.created_at) as last_visit')
            )
            ->groupBy(
                DB::raw('COALESCE(users.name, hotel_bookings.customer_name, "ភ្ញៀវអនឡាញ")'),
                DB::raw('COALESCE(users.email, hotel_bookings.customer_email, "គ្មានអ៊ីមែល")'),
                DB::raw('COALESCE(users.phone, hotel_bookings.customer_phone, "គ្មានលេខទូរស័ព្ទ")')
            );

        if ($request->filled('search')) {
            $search = $request->search;
            $query->havingRaw('name LIKE ? OR email LIKE ? OR phone LIKE ?', ["%{$search}%", "%{$search}%", "%{$search}%"]);
        }

        $customers = $query->orderBy('total_bookings', 'desc')->get();
        $fileName = 'Customer_Analytics_Report_' . date('Y-m-d_H-i') . '.csv';

        $headers = [
            "Content-type"        => "text/csv; charset=UTF-8",
            "Content-Disposition" => "attachment; filename=$fileName",
            "Pragma"              => "no-cache",
            "Cache-Control"       => "must-revalidate, post-check=0, pre-check=0",
            "Expires"             => "0"
        ];

        $columns = ['ឈ្មោះអតិថិជន', 'អ៊ីមែល', 'លេខទូរស័ព្ទ', 'ចំនួនដងកក់សរុប', 'ចំណាយសរុប ($)', 'ចូលស្នាក់នៅចុងក្រោយ'];

        $callback = function() use ($customers, $columns) {
            $file = fopen('php://output', 'w');
            fprintf($file, chr(0xEF).chr(0xBB).chr(0xBF));
            fputcsv($file, $columns);

            foreach ($customers as $c) {
                fputcsv($file, [
                    $c->name,
                    $c->email,
                    $c->phone,
                    $c->total_bookings,
                    number_format($c->lifetime_spend, 2),
                    $c->last_visit ? date('d/m/Y', strtotime($c->last_visit)) : 'N/A'
                ]);
            }

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }

    public function exportPdf(Request $request)
    {
        $query = DB::table('hotel_bookings')
            ->leftJoin('users', 'hotel_bookings.user_id', '=', 'users.id')
            ->select(
                DB::raw('COALESCE(users.name, hotel_bookings.customer_name, "ភ្ញៀវអនឡាញ") as name'),
                DB::raw('COALESCE(users.email, hotel_bookings.customer_email, "គ្មានអ៊ីមែល") as email'),
                DB::raw('COALESCE(users.phone, hotel_bookings.customer_phone, "គ្មានលេខទូរស័ព្ទ") as phone'),
                DB::raw('COUNT(hotel_bookings.id) as total_bookings'),
                DB::raw('COALESCE(SUM(hotel_bookings.total_price), 0) as lifetime_spend'),
                DB::raw('MAX(hotel_bookings.created_at) as last_visit')
            )
            ->groupBy(
                DB::raw('COALESCE(users.name, hotel_bookings.customer_name, "ភ្ញៀវអនឡាញ")'),
                DB::raw('COALESCE(users.email, hotel_bookings.customer_email, "គ្មានអ៊ីមែល")'),
                DB::raw('COALESCE(users.phone, hotel_bookings.customer_phone, "គ្មានលេខទូរស័ព្ទ")')
            );

        if ($request->filled('search')) {
            $search = $request->search;
            $query->havingRaw('name LIKE ? OR email LIKE ? OR phone LIKE ?', ["%{$search}%", "%{$search}%", "%{$search}%"]);
        }

        $customers = $query->orderBy('total_bookings', 'desc')->get();
        $totalSpendSum = $customers->sum('lifetime_spend');

        return view('admin.reportcustomers.print', compact('customers', 'totalSpendSum'));
    }
}
