<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Review;

class ReviewWebController extends Controller
{
    public function store(Request $request)
    {
        $request->validate([
            'room_type_id' => 'required|exists:room_types,id',
            'name' => 'required|string|max:255',
            'rating' => 'required|integer|min:1|max:5',
            'comment' => 'nullable|string',
        ]);

        Review::create($request->all());

        return back()->with('success', 'Review submitted successfully!');
    }
}
