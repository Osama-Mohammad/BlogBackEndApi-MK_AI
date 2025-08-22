<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Post;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PostController extends Controller
{

    public function index()
    {
        $posts = Post::with('comments')->get();
        return response()->json([
            'post' => $posts
        ]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'content' => 'required|string',
            'status' => 'required|string',
            'categories' => 'nullable',
        ]);

        $post = Post::create([
            'user_id' => Auth::id(),
            'title' => $validated['title'],
            'slug' => Str::slug($validated['title']),
            'content' => $validated['content'],
            'status' => $validated['status'],
            'published_at' => now(),
        ]);

        $categoriesId = Category::whereIn('name', $validated['categories'])->pluck('id')->toArray();

        $post->categories()->attach($categoriesId);

        return response()->json([
            'success' => 'Post created successfully',
            'post' => $post
        ]);
    }

    public function destroy(int $id)
    {
        $post = Post::find($id);

        if (!$post) {
            return response()->json([
                'error' => 'Post Not Found'
            ], 404);
        }

        if ($post->user_id !== Auth::id()) {
            abort(403);
        }
        $post->delete();

        return response()->json([
            'success' => 'Post deleted successfully',
        ]);
    }

    public function update(Request $request, int $id)
    {

        $post = Post::findOrFail($id);

        if ($post->user_id !== Auth::id()) {
            return response()->json([
                'error' => 'Unauthorized Actions'
            ], 403);
        }

        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'content' => 'required|string',
            'status' => 'required|string',
        ]);

        $post->update([
            'title' => $validated['title'],
            'slug' => Str::slug($validated['title']),
            'content' => $validated['content'],
            'status' => $validated['status'],
        ]);

        return response()->json([
            'success' => 'Post updated successfully',
            'post' => $post
        ]);
    }
}
