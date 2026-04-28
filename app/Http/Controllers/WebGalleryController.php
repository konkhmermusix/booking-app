<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Gallery;
use App\Models\Hotel;

class WebGalleryController extends Controller
{
    public function index()
    {
        // ទាញយកសណ្ឋាគារដែលមានរូបភាពក្នុង Gallery
        $hotels = Hotel::whereHas('galleries')->get();

        // ទាញយករូបភាព Gallery ទាំងអស់
        $galleries = Gallery::with('hotel')
            ->where('is_active', 1)
            ->orderBy('sort_order', 'asc')
            ->paginate(12); // បែងចែកទំព័រ ម្តង ១២ រូប

        return view('frontend.gallery', compact('galleries', 'hotels'));
    }
}
