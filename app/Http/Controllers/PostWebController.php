<?php

namespace App\Http\Controllers;

use App\Models\Post;
use Illuminate\Http\Request;

class PostWebController extends Controller
{
    public function index(Request $request)
    {
        $query = Post::with('user')->where('status', 'published');

        if ($request->has('search') && !empty($request->search)) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('title', 'LIKE', "%{$search}%")
                    ->orWhere('content', 'LIKE', "%{$search}%");
            });
        }

        $posts = $query->orderBy('created_at', 'desc')->paginate(20);

        if ($request->has('search')) {
            $posts->appends(['search' => $request->search]);
        }

        if ($request->ajax()) {
            return view('frontend.partials.post_list', compact('posts'))->render();
        }

        return view('frontend.posts', compact('posts'));
    }

    public function post_detail($id)
    {
        $post = Post::with('user')->where('status', 'published')->findOrFail($id);

        $post->increment('views');

        $relatedPosts = Post::with('user')
            ->where('status', 'published')
            ->where('id', '!=', $id)
            ->orderBy('created_at', 'desc')
            ->take(3)
            ->get();

        return view('frontend.posts_detail', compact('post', 'relatedPosts'));
    }
}
