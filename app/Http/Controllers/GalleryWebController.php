<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Gallery;
use App\Models\Hotel;

class GalleryWebController extends Controller
{
    public function index()
    {
        $hotels = Hotel::whereHas('galleries')->get();

        $galleries = Gallery::with('hotel')
            ->where('is_active', 1)
            ->orderBy('sort_order', 'asc')
            ->paginate(12);

        return view('frontend.gallery', compact('galleries', 'hotels'));
    }
}
