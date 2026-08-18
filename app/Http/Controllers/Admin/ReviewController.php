<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Review;

class ReviewController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->input('search');
        $status = $request->input('status');

        $reviews = Review::query()
            ->with(['roomType', 'user'])
            ->whereNull('parent_id')
            ->when($search, function ($q) use ($search) {
                return $q->where(function ($inner) use ($search) {
                    $inner->where('name', 'LIKE', "%{$search}%")
                          ->orWhere('comment', 'LIKE', "%{$search}%")
                          ->orWhereHas('roomType', function ($rt) use ($search) {
                              $rt->where('name', 'LIKE', "%{$search}%");
                          });
                });
            })
            ->when($status !== null && $status !== '', function ($q) use ($status) {
                return $q->where('status', $status);
            })
            ->latest()
            ->paginate(10);

        if ($request->ajax()) {
            return view('admin.reviews.partials.reviews_list', compact('reviews'))->render();
        }

        return view('admin.reviews.index', compact('reviews'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'status' => 'required|in:0,1',
            'comment' => 'nullable|string',
        ]);

        $review = Review::findOrFail($id);
        $review->update([
            'status' => $request->status,
            'comment' => $request->comment,
        ]);

        return back()->with('success', 'ស្ថានភាពការវាយតម្លៃត្រូវបានធ្វើបច្ចុប្បន្នភាព');
    }

    public function destroy($id)
    {
        $review = Review::findOrFail($id);
        $review->delete();

        return back()->with('success', 'ការវាយតម្លៃត្រូវបានលុបចេញដោយជោគជ័យ');
    }
}
