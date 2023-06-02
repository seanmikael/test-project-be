<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Category;
use App\Models\Post;
use Illuminate\Support\Facades\Auth;

class PostController extends Controller
{
    public function show() {
        $posts = Post::with('user', 'category')->get();

        return response()->json([
            'message' => 'Posts retrieved successfully',
            'posts' => $posts
        ], 200);
    }

    public function create(Request $request){ 
        $request->validate([
            'content' => 'required|string|max:255',
            'status' => 'required|string|in:Publish,Draft',
            'category_id' => 'required|exists:categories,id'
        ]);
        
        $user = Auth::user();

        if (!$user) {
            // Handle the case when the user is not authenticated
            return response()->json(['message' => 'User not authenticated'], 401);
        }

        $post = $user->posts()->create([
            'content' => $request->content,
            'status' => $request->status,
            'category_id' => $request->category_id,
        ]);

      
    return response()->json([
        'message' => 'post created',
        'post' => $post
    ], 201);
    }

    public function delete($id){
        $post = Post::findOrFail($id);
        if(!$post) return response()->json(['message' => "User does not exist!"], 404);
    
        $post->delete();
        return response()->json(['message' => 'User deleted']);
    }

    public function update(Request $request, $id){
        $request->validate([
            'content' => 'required|string|max:255',
            'category_id' => 'required|exists:categories,id',
            'status' => 'required|string|in:Publish,Draft',
            
        ]);

        $post = Post::findOrFail($id);
        $post->content = $request->content;
        $post->status = $request->status;
        $post->category_id = $request->category_id;
        $post->save();

    return response()->json([
        'message' => 'Post updated',
        'post' => $post
    ], 200);

    }
}
