<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\AboutContent;
use App\Models\HotelHistory;
use App\Models\Gallery;

class AboutWebController extends Controller
{

    public function index()
    {
        // ទាញយកប្រវត្តិដែល Active និងរៀបតាមលំដាប់លេខរៀង
        $histories = HotelHistory::active()->orderBy('order_priority', 'asc')->get();

        // ទាញយកមាតិកាផ្សេងៗ (Vision, Mission...)
        $contents = AboutContent::where('status', true)->get()->keyBy('key');

        $galleries = Gallery::with('hotel')
            ->where('is_active', 1)
            ->orderBy('sort_order', 'asc')
            ->paginate(13);

        return view('frontend.about', compact('histories', 'contents', 'galleries'));
    }
}
