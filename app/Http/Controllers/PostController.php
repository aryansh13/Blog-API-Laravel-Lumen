<?php

namespace App\Http\Controllers;

use App\Models\Post;
use Illuminate\Http\Request;

class PostController extends Controller
{
    // Create Post
    public function store(Request $request)
    {
        $this->validate($request, [
            'title' => 'required',
            'content' => 'required'
        ]);

        $post = Post::create($request->all());
        
        return response()->json([
            'id' => $post->id,
            'title' => $post->title,
            'content' => $post->content,
            'link' => "/posts/{$post->id}"
        ], 201);
    }

    // Get Single Post
    public function show($id)
    {
        $post = Post::find($id);
        
        if (!$post) {
            return response()->json(['message' => 'Post not found'], 404);
        }
        
        return response()->json([
            'id' => $post->id,
            'title' => $post->title,
            'content' => $post->content,
            'link' => "/posts/{$post->id}"
        ], 200);
    }

    // Update Post
    public function update(Request $request, $id)
    {
        $post = Post::find($id);
        
        if (!$post) {
            return response()->json(['message' => 'Post not found'], 404);
        }
        
        // Only update fields that are provided
        if ($request->has('title')) {
            $post->title = $request->title;
        }
        
        if ($request->has('content')) {
            $post->content = $request->content;
        }
        
        $post->save();
        
        return response()->json([
            'id' => $post->id,
            'title' => $post->title,
            'content' => $post->content,
            'link' => "/posts/{$post->id}"
        ], 200);
    }

    // Delete Post
    public function destroy($id)
    {
        $post = Post::find($id);
        
        if (!$post) {
            return response()->json(['message' => 'Post not found'], 404);
        }
        
        $post->delete();
        
        return response()->json([
            'success' => true,
            'deleted_id' => $id
        ], 200);
    }

    // List All Posts
    public function index(Request $request)
    {
        $limit = $request->input('limit', 10);
        $page = $request->input('page', 1);
        
        $posts = Post::skip(($page - 1) * $limit)
                     ->take($limit)
                     ->get();
        
        $totalCount = Post::count();
        $lastPage = ceil($totalCount / $limit);
        
        $data = [];
        foreach ($posts as $post) {
            $data[] = [
                'id' => $post->id,
                'title' => $post->title,
                'content' => $post->content,
                'link' => "/posts/{$post->id}"
            ];
        }
        
        return response()->json([
            'data' => $data,
            'total_count' => $totalCount,
            'limit' => $limit,
            'pagination' => [
                'first_page' => "/posts?page=1",
                'last_page' => "/posts?page={$lastPage}",
                'page' => $page,
                'next_page' => $page < $lastPage ? "/posts?page=" . ($page + 1) : null,
                'prev_page' => $page > 1 ? "/posts?page=" . ($page - 1) : null
            ]
        ], 200);
    }
}