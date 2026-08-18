<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Review;

class PolicyWebController extends Controller
{

    public function terms()
    {
        return view('frontend.terms_conditions');
    }

    public function privacy()
    {
        return view('frontend.privacy_policy');
    }

    public function reviews()
    {

        $reviews = Review::with('roomType')
            ->where('status', 1)
            ->whereNull('parent_id')
            ->latest()
            ->take(20)
            ->paginate(20);

        return view('frontend.reviews', compact('reviews'));
    }
}
