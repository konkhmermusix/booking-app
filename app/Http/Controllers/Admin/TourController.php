<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Tour;
use App\Services\TourService;
use App\Http\Requests\StoreTourRequest;
use Illuminate\Http\Request;

class TourController extends Controller
{
    protected $tourService;

    public function __construct(TourService $tourService)
    {
        $this->tourService = $tourService;
    }

    public function index(Request $request)
    {
        $tours = $this->tourService->getTours($request);

        if ($request->ajax()) {
            return view('admin.tours.partials.tours-list', compact('tours'))->render();
        }

        $totalTours = Tour::count();
        $activeTours = Tour::where('status', 1)->count();
        $inactiveTours = Tour::where('status', 0)->count();
        $withMaps = Tour::whereNotNull('google_map_link')->where('google_map_link', '!=', '')->count();

        return view('admin.tours.index', compact('tours', 'totalTours', 'activeTours', 'inactiveTours', 'withMaps'));
    }


    public function store(StoreTourRequest $request)
    {
        $tour = $this->tourService->createTour($request);

        if ($request->wantsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'រក្សាទុកជោគជ័យ',
                'data' => $tour
            ]);
        }

        return redirect()->route('tours.index')->with('success', 'រក្សាទុកជោគជ័យ');
    }

    public function update(StoreTourRequest $request, Tour $tour)
    {
        $this->tourService->updateTour($request, $tour);

        if ($request->wantsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'កែប្រែជោគជ័យ'
            ]);
        }

        return redirect()->route('tours.index')->with('success', 'កែប្រែជោគជ័យ');
    }

    public function destroy(Tour $tour)
    {
        $this->tourService->deleteTour($tour);

        if (request()->wantsJson() || request()->ajax()) {
            return response()->json([
                'success' => true,
                'message' => 'បានលុបជោគជ័យ'
            ]);
        }

        return redirect()->route('tours.index')->with('success', 'បានលុបជោគជ័យ');
    }
}
