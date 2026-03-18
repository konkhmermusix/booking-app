<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;
use App\Models\Tour;
use App\Services\TourService;
use App\Http\Requests\StoreTourRequest;

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
        return view('admin.tours.index', compact('tours'));
    }

    public function store(StoreTourRequest $request)
    {
        $this->tourService->createTour($request);

        return back()->with('success', 'Tour created');
    }

    public function update(StoreTourRequest $request, Tour $tour)
    {
        $this->tourService->updateTour($request, $tour);

        return back()->with('success', 'Tour updated');
    }

    public function destroy(Tour $tour)
    {
        $this->tourService->deleteTour($tour);

        return back()->with('success', 'Tour deleted');
    }
}
