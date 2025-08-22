<?php

namespace App\Http\Controllers;

use App\Models\Post;
use App\Models\Comment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CommentController extends Controller
{
    public function comment(Request $request, Post $post)
    {
        if (!$post) {
            return response()->json([
                'error' => 'Post Not Found '
            ], 404);
        }
        $validated = $request->validate([
            'content' => "required"
        ]);


        $comment = Comment::Create([
            'post_id' => $post->id,
            'user_id' => Auth::id(),
            'content' => $validated['content'],
        ]);

        return response()->json([
            'success' => 'Comment Created Successfully',
            'Comment Detail' => $comment
        ], 201);
    }

    public function destroy(Request $request)
    {
        $comment = Comment::findORFail($request->id);

        // return response()->json([
        //     'user',
        //     Auth::user()
        // ]);

        if (Auth::id() !== $comment->user_id) {
            return response()->json([
                'error' => 'Unauthorized Action'
            ], 403);
        }

        $comment->delete();

        return response()->json([
            'success' => 'Comment Deleted Successfully',
        ]);
    }
}
