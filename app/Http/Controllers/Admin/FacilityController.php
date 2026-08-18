<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Facility;
use App\Services\FacilityService;
use App\Http\Requests\StoreFacilityRequest;
use App\Http\Requests\UpdateFacilityRequest;
use Illuminate\Http\Request;

class FacilityController extends Controller
{
    protected $facilityService;

    public function __construct(FacilityService $facilityService)
    {
        $this->facilityService = $facilityService;
    }

    public function index()
    {
        $facilities = $this->facilityService->getAllActive();
        return view('admin.facilities.index', compact('facilities'));
    }

    public function store(StoreFacilityRequest $request)
    {
        $this->facilityService->createFacility($request->validated());
        return redirect()->route('facilities.index')->with('success', 'បង្កើតជោគជ័យ');
    }

    public function show(Facility $facility)
    {
        return response()->json($facility); // ឬ return view
    }

    public function update(UpdateFacilityRequest $request, Facility $facility)
    {
        $this->facilityService->updateFacility($facility, $request->validated());
        return redirect()->route('facilities.index')->with('success', 'កែប្រែជោគជ័យ');
    }

    public function destroy(Facility $facility)
    {
        $facility->delete();
        return redirect()->route('facilities.index')->with('success', 'លុបជោគជ័យ');
    }
}
