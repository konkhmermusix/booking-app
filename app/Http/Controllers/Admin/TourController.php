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
            // ប្តូរពី response()->json() មក render ជា View partial វិញ
            return view('admin.tours.partials.tours-list', compact('tours'))->render();
        }

        return view('admin.tours.index', compact('tours'));
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
        return response()->json([
            'success' => true,
            'message' => 'បានលុបជោគជ័យ'
        ]);
    }
}
