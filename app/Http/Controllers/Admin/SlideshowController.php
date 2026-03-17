<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Slideshow;
use App\Services\SlideshowService;
use Illuminate\Http\Request;

class SlideshowController extends Controller
{
    protected $service;

    public function __construct(SlideshowService $service)
    {
        $this->service = $service;
    }

    public function index()
    {
        $slides = $this->service->getAllSlides();
        return view('admin.slideshows.index', compact('slides'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'title' => 'nullable|string|max:255',
            'subtitle' => 'nullable|string',
            'image' => 'required|image|mimes:jpeg,png,jpg|max:2048',
            'order_column' => 'required|integer',
        ]);

        $this->service->createSlide($data);
        return back()->with('success', 'បដារបានបន្ថែម ជោគជ័យ!');
    }

    public function update(Request $request, Slideshow $slideshow)
    {
        $data = $request->validate([
            'title' => 'nullable|string|max:255',
            'subtitle' => 'nullable|string',
            'image' => 'nullable|image|mimes:jpeg,png,jpg|max:22048',
            'order_column' => 'required|integer',
            'is_active' => 'boolean'
        ]);

        $data['is_active'] = $request->has('is_active');
        $this->service->updateSlide($slideshow, $data);
        return back()->with('success', 'បដារបានកែប្រែ បានជោគជ័យ!');
    }

    public function destroy(Slideshow $slideshow)
    {
        $this->service->deleteSlide($slideshow);
        return back()->with('success', 'បដារបានលុប ដោយជោគជ័យ!');
    }
}
