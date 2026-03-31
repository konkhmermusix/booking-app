<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Slideshow;
use Illuminate\Http\Request;

class SlideshowController extends Controller
{
    public function index()
    {
        $slides = Slideshow::where('is_active', true)
            ->orderBy('order_column', 'asc')
            ->get()
            ->map(function ($slide) {
                return [
                    'id' => $slide->id,
                    'title' => $slide->title,
                    'subtitle' => $slide->subtitle,
                    // បញ្ជាក់៖ ត្រូវប្រាកដថាអ្នកបានប្រើ Storage Link ដើម្បីឱ្យ Path រូបភាពត្រឹមត្រូវ
                    'image_url' => asset('storage/' . $slide->image_path),
                ];
            });

        return response()->json($slides);
    }
}
