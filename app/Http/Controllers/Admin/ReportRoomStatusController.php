<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Room;

class ReportRoomStatusController extends Controller
{
    public function index(Request $request)
    {
        $query = Room::with(['roomType', 'hotelBookings' => function ($bq) {
            $bq->whereIn('status', ['confirmed', 'checked_in'])->orderBy('check_out', 'desc');
        }]);

        // Filter by Status
        if ($request->filled('status') && $request->status !== 'all') {
            $query->where('rooms.status', $request->status);
        }

        // Filter by Floor
        if ($request->filled('floor') && $request->floor !== 'all') {
            $query->where('floor', $request->floor);
        }

        // Filter by Search
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('room_number', 'like', "%{$search}%")
                  ->orWhereHas('roomType', function ($rtq) use ($search) {
                      $rtq->where('name', 'like', "%{$search}%");
                  });
            });
        }

        $allRooms = $query->orderBy('floor', 'asc')->orderBy('room_number', 'asc')->get();

        // 1. KPI Stats
        $totalRooms = Room::count();
        $availableRoomsCount = Room::where('status', 'available')->count();
        $occupiedRoomsCount = Room::where('status', 'booked')->count();
        $maintenanceRoomsCount = Room::where('status', 'maintenance')->count();
        $occupancyRate = $totalRooms > 0 ? round(($occupiedRoomsCount / $totalRooms) * 100, 1) : 0;

        // Group rooms by Floor for Grid View
        $roomsByFloor = $allRooms->groupBy(function ($r) {
            return $r->floor ? 'ជាន់ទី ' . $r->floor : 'ជាន់ដី (Ground Floor)';
        });

        // Floor Options List
        $floorsList = Room::select('floor')->distinct()->orderBy('floor', 'asc')->pluck('floor');

        if ($request->ajax()) {
            return view('admin.reportroomstatus.partials.report_content', compact(
                'allRooms',
                'roomsByFloor',
                'floorsList',
                'totalRooms',
                'availableRoomsCount',
                'occupiedRoomsCount',
                'maintenanceRoomsCount',
                'occupancyRate'
            ));
        }

        return view('admin.reportroomstatus.index', compact(
            'allRooms',
            'roomsByFloor',
            'floorsList',
            'totalRooms',
            'availableRoomsCount',
            'occupiedRoomsCount',
            'maintenanceRoomsCount',
            'occupancyRate'
        ));
    }

    public function exportExcel(Request $request)
    {
        $query = Room::with('roomType');

        if ($request->filled('status') && $request->status !== 'all') {
            $query->where('status', $request->status);
        }

        if ($request->filled('floor') && $request->floor !== 'all') {
            $query->where('floor', $request->floor);
        }

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('room_number', 'like', "%{$search}%")
                  ->orWhereHas('roomType', function ($rtq) use ($search) {
                      $rtq->where('name', 'like', "%{$search}%");
                  });
            });
        }

        $rooms = $query->orderBy('floor', 'asc')->orderBy('room_number', 'asc')->get();
        $fileName = 'Room_Status_Report_' . date('Y-m-d_H-i') . '.csv';

        $headers = [
            "Content-type"        => "text/csv; charset=UTF-8",
            "Content-Disposition" => "attachment; filename=$fileName",
            "Pragma"              => "no-cache",
            "Cache-Control"       => "must-revalidate, post-check=0, pre-check=0",
            "Expires"             => "0"
        ];

        $columns = ['លេខបន្ទប់', 'ជាន់ទី', 'ប្រភេទបន្ទប់', 'តម្លៃ១យប់ ($)', 'ស្ថានភាពបច្ចុប្បន្ន'];

        $callback = function() use ($rooms, $columns) {
            $file = fopen('php://output', 'w');
            fprintf($file, chr(0xEF).chr(0xBB).chr(0xBF));
            fputcsv($file, $columns);

            foreach ($rooms as $r) {
                $statusLabel = match($r->status) {
                    'available'   => 'ទំនេរ',
                    'booked'      => 'មានគេកក់/ស្នាក់នៅ',
                    'maintenance' => 'ជួសជុល',
                    default       => $r->status
                };

                fputcsv($file, [
                    $r->room_number,
                    $r->floor ? 'ជាន់ទី ' . $r->floor : 'ជាន់ដី',
                    $r->roomType->name ?? 'N/A',
                    number_format($r->roomType->base_price ?? 0, 2),
                    $statusLabel
                ]);
            }

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }

    public function exportPdf(Request $request)
    {
        $query = Room::with('roomType');

        if ($request->filled('status') && $request->status !== 'all') {
            $query->where('status', $request->status);
        }

        if ($request->filled('floor') && $request->floor !== 'all') {
            $query->where('floor', $request->floor);
        }

        $rooms = $query->orderBy('floor', 'asc')->orderBy('room_number', 'asc')->get();
        $totalRooms = $rooms->count();
        $availableCount = $rooms->where('status', 'available')->count();
        $bookedCount = $rooms->where('status', 'booked')->count();
        $maintenanceCount = $rooms->where('status', 'maintenance')->count();

        return view('admin.reportroomstatus.print', compact('rooms', 'totalRooms', 'availableCount', 'bookedCount', 'maintenanceCount'));
    }
}
