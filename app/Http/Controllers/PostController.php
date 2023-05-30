<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Post;
use Illuminate\Support\Facades\Auth;

class PostController extends Controller
{
    public function show() {
        $posts = Post::All();

        return $posts;
    }

    public function create(Request $request){ 
        $request->validate([
            'content' => 'required | string | max:255',
            'status' => 'required|string|in:Publish,Draft',
        ]);
        
        $user = Auth::user();

        if (!$user) {
            // Handle the case when the user is not authenticated
            return response()->json(['message' => 'User not authenticated'], 401);
        }

        $post = $user->posts()->create([
            'content' => $request->content,
            'status' => $request->status,
        ]);

      
    return response()->json([
        'message' => 'post created',
        'post' => $post
    ]);
    }
}
