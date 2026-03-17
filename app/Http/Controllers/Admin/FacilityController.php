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

    // ១. បង្ហាញបញ្ជី (GET: /admin/facilities)
    public function index()
    {
        $facilities = $this->facilityService->getAllActive();
        return view('admin.facilities.index', compact('facilities'));
    }

    // ២. បង្ហាញ Form បង្កើត (GET: /admin/facilities/create)
    // ចំណាំ៖ ដោយសារអ្នកប្រើ Modal ក្នុង index ខ្លះប្រហែលមិនបាច់ប្រើ Method នេះទេ
    public function create()
    {
        return view('admin.facilities.create');
    }

    // ៣. រក្សាទុកទិន្នន័យថ្មី (POST: /admin/facilities)
    public function store(StoreFacilityRequest $request)
    {
        $this->facilityService->createFacility($request->validated());
        return redirect()->route('facilities.index')->with('success', 'បង្កើតជោគជ័យ!');
    }

    // ៤. បង្ហាញលម្អិត (GET: /admin/facilities/{facility})
    public function show(Facility $facility)
    {
        return response()->json($facility); // ឬ return view
    }

    // ៥. បង្ហាញ Form កែប្រែ (GET: /admin/facilities/{facility}/edit)
    public function edit(Facility $facility)
    {
        return view('admin.facilities.edit', compact('facility'));
    }

    // ៦. បច្ចុប្បន្នភាពទិន្នន័យ (PUT/PATCH: /admin/facilities/{facility})
    public function update(UpdateFacilityRequest $request, Facility $facility)
    {
        $this->facilityService->updateFacility($facility, $request->validated());
        return redirect()->route('facilities.index')->with('success', 'កែប្រែជោគជ័យ!');
    }

    // ៧. លុបទិន្នន័យ (DELETE: /admin/facilities/{facility})
    public function destroy(Facility $facility)
    {
        $facility->delete();
        return redirect()->route('facilities.index')->with('success', 'លុបជោគជ័យ!');
    }
}
