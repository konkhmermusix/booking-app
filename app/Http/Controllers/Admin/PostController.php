<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Post;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Exception;

class PostController extends Controller
{
    public function index(Request $request)
    {
        $query = Post::with('user')->latest();

        if ($request->filled('search')) {
            $query->where('title', 'like', '%' . $request->search . '%');
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $posts = $query->paginate(10);

        if ($request->ajax()) {
            return view('admin.posts.partials.post_list', compact('posts'))->render();
        }

        $totalPosts = Post::count();
        $publishedPosts = Post::where('status', 'published')->count();
        $draftPosts = Post::where('status', 'draft')->count();
        $privatePosts = Post::where('status', 'private')->count();

        return view('admin.posts.index', compact('posts', 'totalPosts', 'publishedPosts', 'draftPosts', 'privatePosts'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'title'   => 'required|string|max:255',
            'content' => 'required|string',
            'status'  => 'required|in:draft,published,private',
            'images.*' => 'nullable|image|mimes:jpeg,png,jpg,webp,gif|max:20480'
        ]);

        try {
            $post = new Post();
            $post->title = $request->title;

            $slugBase = Str::slug($request->title, '-');
            if (empty($slugBase)) {
                $slugBase = str_replace(' ', '-', $request->title);
            }

            $post->slug = $slugBase . '-' . rand(1000, 9999);

            $post->content = $request->content;
            $post->status = $request->status;
            $post->user_id = auth()->id() ?? 1;

            $imagePaths = [];
            if ($request->hasFile('images')) {
                foreach ($request->file('images') as $file) {
                    $imagePaths[] = $file->store('posts', 'public');
                }
            }

            $post->images = $imagePaths;
            $post->save();

            return response()->json(['success' => true, 'message' => 'បានបង្កើតព័ត៌មានថ្មីរួចរាល់', 'data' => $post], 200);
        } catch (Exception $e) {
            Log::error("Post Store Error: " . $e->getMessage());
            return response()->json(['success' => false, 'message' => 'កំហុស៖ ' . $e->getMessage()], 500);
        }
    }

    public function update(Request $request, Post $post)
    {
        $request->validate([
            'title'    => 'required|string|max:255',
            'content'  => 'required|string',
            'status'   => 'required|in:draft,published,private',
            'images.*' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:20480'
        ]);

        try {
            $imagePaths = $post->images ?? [];

            if ($request->hasFile('images')) {
                foreach ($request->file('images') as $image) {
                    $imagePaths[] = $image->store('posts', 'public');
                }
            }

            $slugBase = Str::slug($request->title, '-');
            if (empty($slugBase)) {
                $slugBase = str_replace(' ', '-', $request->title);
            }
            $newSlug = $slugBase . '-' . $post->id;

            $post->update([
                'title'   => $request->title,
                'slug'    => $newSlug,
                'content' => $request->content,
                'images'  => $imagePaths,
                'status'  => $request->status,
            ]);

            return response()->json(['success' => true, 'message' => 'ធ្វើបច្ចុប្បន្នភាពបានជោគជ័យ']);
        } catch (Exception $e) {
            Log::error("Post Update Error: " . $e->getMessage());
            return response()->json(['success' => false, 'message' => 'កំហុស៖ ' . $e->getMessage()], 500);
        }
    }

    public function destroy(Post $post)
    {
        try {
            if (!empty($post->images)) {
                foreach ($post->images as $path) {
                    if (Storage::disk('public')->exists($path)) {
                        Storage::disk('public')->delete($path);
                    }
                }
            }

            $post->delete();
            return back()->with('success', 'លុបអត្ថបទព័ត៌មានរួចរាល់');
        } catch (Exception $e) {
            Log::error("Post Delete Error: " . $e->getMessage());
            return back()->with('error', 'មិនអាចលុបបានទេ៖ ' . $e->getMessage());
        }
    }

    public function destroyImage(Post $post, Request $request)
    {
        $request->validate([
            'image_path' => 'required|string'
        ]);

        try {
            $targetPath = $request->image_path;
            $currentImages = $post->images ?? [];

            if (($key = array_search($targetPath, $currentImages)) !== false) {

                if (Storage::disk('public')->exists($targetPath)) {
                    Storage::disk('public')->delete($targetPath);
                }

                unset($currentImages[$key]);

                $post->update([
                    'images' => array_values($currentImages)
                ]);

                return response()->json(['success' => true, 'message' => 'លុបរូបភាពដោយជោគជ័យ']);
            }

            return response()->json(['success' => false, 'message' => 'រកមិនឃើញរូបភាព'], 404);
        } catch (Exception $e) {
            Log::error("Post Image Single Delete Error: " . $e->getMessage());
            return response()->json(['success' => false, 'message' => $e->getMessage()], 500);
        }
    }
}
