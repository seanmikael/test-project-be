<?php

namespace App\Http\Controllers;

use App\Models\Post;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PostController extends Controller
{
    public function show()
    {
        $posts = Post::with('user', 'category')->get();

        return response()->json([
            'message' => 'Posts retrieved successfully',
            'posts' => $posts,
        ], 200);
    }

    //for view page
    public function get($id)
    {
        $post = Post::with('user', 'category')->find($id);

        if (!$post) {
            return response()->json([
                'message' => 'Post not found',
            ], 404);
        }

        return response()->json([
            'message' => 'Post retrieved successfully',
            'post' => $post,
        ], 200);
    }

    public function create(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'content' => 'required|string|max:255',
            'status' => 'required|string|in:Published,Draft',
            'category_id' => 'required|exists:categories,id',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        $user = Auth::user();

        if (!$user) {
            return response()->json(['message' => 'User not authenticated'], 401);
        }

        $imagePath = null;

        if ($request->hasFile('image')) {
            $image = $request->file('image');
            $imagePath = $image->store('images', 'public');
        }

        $post = $user->posts()->create([
            'title' => $request->input('title'),
            'content' => $request->input('content'),
            'status' => $request->input('status'),
            'category_id' => $request->input('category_id'),
            'image_path' => $imagePath,
        ]);

        return response()->json(['message' => 'Post created', 'post' => $post], 201);
    }

    public function delete($id)
    {
        $post = Post::findOrFail($id);
        if (!$post) {
            return response()->json(['message' => "User does not exist!"], 404);
        }

        $post->delete();
        return response()->json(['message' => 'User deleted']);
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'content' => 'required|string|max:255',
            'category_id' => 'required|exists:categories,id',
            'status' => 'required|string|in:Published,Draft',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        $post = Post::findOrFail($id);
        $post->title = $request->title;
        $post->content = $request->content;
        $post->status = $request->status;
        $post->category_id = $request->category_id;

        if ($request->hasFile('image')) {
            $image = $request->file('image');
            $imagePath = $image->store('images', 'public');
            $post->image_path = $imagePath;
        }

        $post->save();

        return response()->json([
            'message' => 'Post updated',
            'post' => $post,
        ], 200);
    }

}
