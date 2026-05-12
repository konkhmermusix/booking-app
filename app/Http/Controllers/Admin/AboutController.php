<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Services\AboutService;
use Illuminate\Http\Request;
use App\Models\AboutContent;
use App\Models\HotelHistory;
use Illuminate\Support\Facades\Storage;

class AboutController extends Controller
{
    protected $aboutService;

    public function __construct(AboutService $aboutService)
    {
        $this->aboutService = $aboutService;
    }

    public function index()
    {
        $aboutContents = AboutContent::latest()->get();
        $hotelHistories = HotelHistory::orderBy('order_priority', 'asc')->get();

        return view('admin.abouts.index', compact('aboutContents', 'hotelHistories'));
    }

    // --- About Content Section ---

    public function storeAbout(Request $request)
    {
        $data = $request->validate([
            'key' => 'required',
            'title_kh' => 'required',
            'content_kh' => 'required',
            'image' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
            'status' => 'required|boolean'
        ]);

        if ($request->hasFile('image')) {
            $data['image'] = $request->file('image')->store('abouts', 'public');
        }

        AboutContent::create($data);
        return back()->with('success', 'រក្សាទុកមាតិកាជោគជ័យ');
    }

    public function updateAbout(Request $request, $id)
    {
        $content = AboutContent::findOrFail($id);
        $data = $request->all();

        if ($request->hasFile('image')) {
            if ($content->image) Storage::disk('public')->delete($content->image);
            $data['image'] = $request->file('image')->store('abouts', 'public');
        }

        $content->update($data);
        return back()->with('success', 'កែប្រែមាតិកាជោគជ័យ');
    }

    public function destroyAbout($id)
    {
        $content = AboutContent::findOrFail($id);
        if ($content->image) Storage::disk('public')->delete($content->image);
        $content->delete();
        return back()->with('success', 'លុបមាតិកាបានជោគជ័យ!');
    }

    // --- Hotel History Section ---

    public function storeHistory(Request $request)
    {
        $data = $request->validate([
            'year' => 'required',
            'title_kh' => 'required',
            'description_kh' => 'required',
            'order_priority' => 'required|integer',
            'status' => 'required|boolean'
        ]);

        HotelHistory::create($data);
        return back()->with('success', 'រក្សាទុកប្រវត្តិជោគជ័យ');
    }

    public function updateHistory(Request $request, $id)
    {
        $history = HotelHistory::findOrFail($id);
        $history->update($request->all());
        return back()->with('success', 'កែប្រែប្រវត្តិជោគជ័យ');
    }

    public function destroyHistory($id)
    {
        HotelHistory::findOrFail($id)->delete();
        return back()->with('success', 'លុបប្រវត្តិបានជោគជ័យ!');
    }
}
