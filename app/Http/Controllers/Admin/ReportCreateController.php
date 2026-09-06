<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\HotelBooking;
use App\Models\MeetingBooking;
use App\Models\Payment;
use App\Models\User;
use App\Models\Room;
use App\Models\RoomType;
use App\Models\Promotion;
use App\Models\Review;
use App\Models\Contact;
use App\Models\Tour;
use App\Models\Post;
use App\Models\Facility;
use App\Models\ContactSetting;

class ReportCreateController extends Controller
{
    /**
     * Display custom report generator page.
     */
    public function index(Request $request)
    {
        $tableType = $request->input('table', 'room_bookings');
        $period = $request->input('period', 'this_month');
        $status = $request->input('status', 'all');
        $search = $request->input('search', '');
        $startDate = $request->input('start_date');
        $endDate = $request->input('end_date');
        $perPage = $request->input('per_page', 15);

        $exchangeRate = ContactSetting::getExchangeRate(4100);

        $reportData = $this->buildReportQuery($tableType, $period, $status, $search, $startDate, $endDate);
        
        $records = $reportData['query']->paginate($perPage)->withQueryString();
        $summary = $reportData['summary'];

        return view('admin.reportscreate.index', compact(
            'tableType',
            'period',
            'status',
            'search',
            'startDate',
            'endDate',
            'records',
            'summary',
            'exchangeRate'
        ));
    }

    /**
     * Export dynamic report to Excel/CSV with UTF-8 BOM support for Khmer.
     */
    public function exportExcel(Request $request)
    {
        $tableType = $request->input('table', 'room_bookings');
        $period = $request->input('period', 'this_month');
        $status = $request->input('status', 'all');
        $search = $request->input('search', '');
        $startDate = $request->input('start_date');
        $endDate = $request->input('end_date');

        $reportData = $this->buildReportQuery($tableType, $period, $status, $search, $startDate, $endDate);
        $records = $reportData['query']->get();

        $fileName = 'Report_' . $tableType . '_' . date('Y-m-d_H-i') . '.csv';

        $headers = [
            "Content-type"        => "text/csv; charset=UTF-8",
            "Content-Disposition" => "attachment; filename=$fileName",
            "Pragma"              => "no-cache",
            "Cache-Control"       => "must-revalidate, post-check=0, pre-check=0",
            "Expires"             => "0"
        ];

        $callback = function () use ($tableType, $records) {
            $file = fopen('php://output', 'w');
            fprintf($file, chr(0xEF) . chr(0xBB) . chr(0xBF)); // UTF-8 BOM

            if ($tableType === 'room_bookings') {
                fputcsv($file, ['លេខកូដកក់', 'ឈ្មោះអតិថិជន', 'លេខទូរស័ព្ទ', 'បន្ទប់/ប្រភេទ', 'ថ្ងៃចូល', 'ថ្ងៃចេញ', 'ស្ថានភាព', 'តម្លៃសរុប ($)', 'ថ្ងៃបង្កើត']);
                foreach ($records as $row) {
                    fputcsv($file, [
                        $row->booking_code,
                        $row->customer_name ?: ($row->user->name ?? 'N/A'),
                        $row->customer_phone ?: ($row->user->phone ?? 'N/A'),
                        $row->room->room_number ?? 'N/A',
                        $row->check_in,
                        $row->check_out,
                        $row->status,
                        number_format($row->total_price, 2),
                        $row->created_at->format('Y-m-d H:i')
                    ]);
                }
            } elseif ($tableType === 'meeting_bookings') {
                fputcsv($file, ['លេខកូដកក់', 'ឈ្មោះអតិថិជន', 'លេខទូរស័ព្ទ', 'សាលប្រជុំ', 'ថ្ងៃចាប់ផ្តើម', 'ថ្ងៃបញ្ចប់', 'ចំនួនម៉ោង', 'ស្ថានភាព', 'តម្លៃសរុប ($)', 'ថ្ងៃបង្កើត']);
                foreach ($records as $row) {
                    fputcsv($file, [
                        $row->booking_code,
                        $row->customer_name ?: ($row->user->name ?? 'N/A'),
                        $row->customer_phone ?: ($row->user->phone ?? 'N/A'),
                        $row->room->room_number ?? 'សាលប្រជុំ',
                        $row->start_date,
                        $row->end_date,
                        $row->total_hours ?? 1,
                        $row->status,
                        number_format($row->total_price, 2),
                        $row->created_at->format('Y-m-d H:i')
                    ]);
                }
            } elseif ($tableType === 'payments') {
                fputcsv($file, ['លេខកូដប្រតិបត្តិការ', 'ប្រភេទកក់', 'វិធីសាស្ត្រ', 'ចំនួនប្រាក់ ($)', 'ស្ថានភាព', 'ថ្ងៃបង់ប្រាក់']);
                foreach ($records as $row) {
                    $type = $row->hotel_booking_id ? 'កក់បន្ទប់ (' . ($row->hotelBooking->booking_code ?? '') . ')' : ($row->meeting_booking_id ? 'កក់សាលប្រជុំ (' . ($row->meetingBooking->booking_code ?? '') . ')' : 'ផ្សេងៗ');
                    fputcsv($file, [
                        $row->transaction_id ?: 'TRX-' . $row->id,
                        $type,
                        strtoupper($row->method),
                        number_format($row->amount, 2),
                        $row->status,
                        $row->created_at->format('Y-m-d H:i')
                    ]);
                }
            } elseif ($tableType === 'customers') {
                fputcsv($file, ['ID', 'ឈ្មោះ', 'អ៊ីមែល', 'លេខទូរស័ព្ទ', 'តួនាទី', 'IP ចូលចុងក្រោយ', 'ថ្ងៃចុះឈ្មោះ']);
                foreach ($records as $row) {
                    fputcsv($file, [
                        $row->id,
                        $row->name,
                        $row->email,
                        $row->phone ?: 'N/A',
                        $row->role,
                        $row->last_login_ip ?: 'N/A',
                        $row->created_at->format('Y-m-d H:i')
                    ]);
                }
            } elseif ($tableType === 'rooms') {
                fputcsv($file, ['លេខបន្ទប់', 'ប្រភេទបន្ទប់', 'តម្លៃ/យប់ ($)', 'សមត្ថភាព', 'ស្ថានភាព', 'ថ្ងៃបង្កើត']);
                foreach ($records as $row) {
                    fputcsv($file, [
                        $row->room_number,
                        $row->roomType->name ?? 'N/A',
                        number_format($row->price_per_night, 2),
                        $row->capacity . ' នាក់',
                        $row->status,
                        $row->created_at->format('Y-m-d H:i')
                    ]);
                }
            } elseif ($tableType === 'promotions') {
                fputcsv($file, ['កូដបញ្ចុះតម្លៃ', 'ឈ្មោះ', 'ភាគរយ (%)', 'ថ្ងៃចាប់ផ្តើម', 'ថ្ងៃបញ្ចប់', 'ស្ថានភាព']);
                foreach ($records as $row) {
                    fputcsv($file, [
                        $row->code,
                        $row->title,
                        $row->discount_percentage . '%',
                        $row->start_date,
                        $row->end_date,
                        $row->is_active ? 'សកម្ម' : 'អសកម្ម'
                    ]);
                }
            } elseif ($tableType === 'reviews') {
                fputcsv($file, ['ID', 'ឈ្មោះអតិថិជន', 'ពិន្ទុ (Stars)', 'មតិយោបល់', 'ថ្ងៃបង្កើត']);
                foreach ($records as $row) {
                    fputcsv($file, [
                        $row->id,
                        $row->user->name ?? 'N/A',
                        $row->rating . ' / 5',
                        $row->comment,
                        $row->created_at->format('Y-m-d H:i')
                    ]);
                }
            } elseif ($tableType === 'contacts') {
                fputcsv($file, ['ID', 'ឈ្មោះ', 'អ៊ីមែល', 'លេខទូរស័ព្ទ', 'ប្រធានបទ', 'សារ', 'ថ្ងៃផ្ញើ']);
                foreach ($records as $row) {
                    fputcsv($file, [
                        $row->id,
                        $row->name,
                        $row->email,
                        $row->phone ?: 'N/A',
                        $row->subject,
                        $row->message,
                        $row->created_at->format('Y-m-d H:i')
                    ]);
                }
            }

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }

    /**
     * Export dynamic report to PDF (Print View).
     */
    public function exportPdf(Request $request)
    {
        $tableType = $request->input('table', 'room_bookings');
        $period = $request->input('period', 'this_month');
        $status = $request->input('status', 'all');
        $search = $request->input('search', '');
        $startDate = $request->input('start_date');
        $endDate = $request->input('end_date');

        $exchangeRate = ContactSetting::getExchangeRate(4100);
        $reportData = $this->buildReportQuery($tableType, $period, $status, $search, $startDate, $endDate);
        $records = $reportData['query']->get();
        $summary = $reportData['summary'];

        return view('admin.reportscreate.print', compact(
            'tableType',
            'period',
            'status',
            'startDate',
            'endDate',
            'records',
            'summary',
            'exchangeRate'
        ));
    }

    /**
     * Helper to build query & calculate summary.
     */
    private function buildReportQuery($tableType, $period, $status, $search, $startDate, $endDate)
    {
        switch ($tableType) {
            case 'meeting_bookings':
                $query = MeetingBooking::with(['user', 'room']);
                $amountColumn = 'total_price';
                $searchFields = ['booking_code', 'customer_name', 'customer_phone'];
                break;

            case 'payments':
                $query = Payment::with(['hotelBooking', 'meetingBooking']);
                $amountColumn = 'amount';
                $searchFields = ['transaction_id', 'method'];
                break;

            case 'customers':
                $query = User::query();
                $amountColumn = null;
                $searchFields = ['name', 'email', 'phone'];
                break;

            case 'rooms':
                $query = Room::with('roomType');
                $amountColumn = 'price_per_night';
                $searchFields = ['room_number'];
                break;

            case 'promotions':
                $query = Promotion::query();
                $amountColumn = null;
                $searchFields = ['code', 'title'];
                break;

            case 'reviews':
                $query = Review::with('user');
                $amountColumn = null;
                $searchFields = ['comment'];
                break;

            case 'contacts':
                $query = Contact::query();
                $amountColumn = null;
                $searchFields = ['name', 'email', 'phone', 'subject'];
                break;

            case 'tours':
                $query = Tour::query();
                $amountColumn = 'price';
                $searchFields = ['title'];
                break;

            case 'posts':
                $query = Post::query();
                $amountColumn = null;
                $searchFields = ['title'];
                break;

            case 'facilities':
                $query = Facility::query();
                $amountColumn = null;
                $searchFields = ['name'];
                break;

            case 'room_types':
                $query = RoomType::query();
                $amountColumn = 'base_price';
                $searchFields = ['name'];
                break;

            case 'room_bookings':
            default:
                $query = HotelBooking::with(['user', 'room.roomType']);
                $amountColumn = 'total_price';
                $searchFields = ['booking_code', 'customer_name', 'customer_phone'];
                break;
        }

        // 1. Period Filtering
        switch ($period) {
            case 'today':
                $query->whereDate('created_at', today());
                break;
            case 'yesterday':
                $query->whereDate('created_at', today()->subDay());
                break;
            case 'this_week':
                $query->whereBetween('created_at', [now()->startOfWeek(), now()->endOfWeek()]);
                break;
            case 'last_7_days':
                $query->where('created_at', '>=', now()->subDays(7)->startOfDay());
                break;
            case 'this_month':
                $query->whereMonth('created_at', now()->month)->whereYear('created_at', now()->year);
                break;
            case 'last_month':
                $query->whereMonth('created_at', now()->subMonth()->month)->whereYear('created_at', now()->subMonth()->year);
                break;
            case 'this_year':
                $query->whereYear('created_at', now()->year);
                break;
            case 'custom':
                if ($startDate && $endDate) {
                    $query->whereBetween('created_at', [$startDate . ' 00:00:00', $endDate . ' 23:59:59']);
                }
                break;
        }

        // 2. Status Filtering
        if ($status && $status !== 'all') {
            if ($tableType === 'customers') {
                $query->where('role', $status);
            } elseif ($tableType === 'promotions') {
                $query->where('is_active', $status === 'active' ? 1 : 0);
            } else {
                if (\Schema::hasColumn($query->getModel()->getTable(), 'status')) {
                    $query->where('status', $status);
                }
            }
        }

        // 3. Search Filtering
        if (!empty($search)) {
            $query->where(function ($q) use ($searchFields, $search) {
                foreach ($searchFields as $field) {
                    $q->orWhere($field, 'like', "%{$search}%");
                }
            });
        }

        // Copy query for calculating totals before applying ordering
        $countQuery = clone $query;
        $totalRecords = $countQuery->count();
        $totalAmountUsd = $amountColumn ? (float) $countQuery->sum($amountColumn) : 0;

        $summary = [
            'total_records' => $totalRecords,
            'total_amount_usd' => $totalAmountUsd,
        ];

        return [
            'query' => $query->orderBy('created_at', 'desc'),
            'summary' => $summary,
        ];
    }
}
