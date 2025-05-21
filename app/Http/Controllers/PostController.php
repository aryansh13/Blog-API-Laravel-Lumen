<?php

namespace App\Http\Controllers;

use App\Models\Post;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PostController extends Controller
{
    // Create Post
    public function store(Request $request)
    {
        $this->validate($request, [
            'title' => 'required|max:100',
            'content' => 'required',
            'status' => 'in:draft,published',
            'user_id' => 'required|exists:users,id'
        ]);

        $data = $request->all();
        if (!isset($data['status'])) {
            $data['status'] = 'draft';
        }

        $post = Post::create($data);

        return response()->json([
            'id' => $post->id,
            'title' => $post->title,
            'status' => $post->status,
            'content' => $post->content,
            'user_id' => $post->user_id,
            'link' => "/posts/{$post->id}"
        ], 201);
    }

    // Get Single Post
    public function show($id)
    {
        $post = Post::with('user')->find($id);

        if (!$post) {
            return response()->json(['message' => 'Post not found'], 404);
        }

        return response()->json([
            'id' => $post->id,
            'title' => $post->title,
            'status' => $post->status,
            'content' => $post->content,
            'user_id' => $post->user_id,
            'user' => [
                'id' => $post->user->id,
                'name' => $post->user->name
            ],
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

        $this->validate($request, [
            'title' => 'sometimes|max:100',
            'status' => 'sometimes|in:draft,published',
            'content' => 'sometimes',
            'user_id' => 'sometimes|exists:users,id'
        ]);

        // Only update fields that are provided
        if ($request->has('title')) {
            $post->title = $request->title;
        }

        if ($request->has('content')) {
            $post->content = $request->content;
        }

        if ($request->has('status')) {
            $post->status = $request->status;
        }

        if ($request->has('user_id')) {
            $post->user_id = $request->user_id;
        }

        $post->save();

        return response()->json([
            'id' => $post->id,
            'title' => $post->title,
            'status' => $post->status,
            'content' => $post->content,
            'user_id' => $post->user_id,
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

    // Publish Post (Editor only)
    public function publish($id)
    {
        $post = Post::find($id);

        if (!$post) {
            return response()->json(['message' => 'Post not found'], 404);
        }

        $post->status = 'published';
        $post->save();

        return response()->json([
            'id' => $post->id,
            'title' => $post->title,
            'status' => $post->status,
            'content' => $post->content,
            'user_id' => $post->user_id,
            'link' => "/posts/{$post->id}"
        ], 200);
    }

    // Unpublish Post (Editor only)
    public function unpublish($id)
    {
        $post = Post::find($id);

        if (!$post) {
            return response()->json(['message' => 'Post not found'], 404);
        }

        $post->status = 'draft';
        $post->save();

        return response()->json([
            'id' => $post->id,
            'title' => $post->title,
            'status' => $post->status,
            'content' => $post->content,
            'user_id' => $post->user_id,
            'link' => "/posts/{$post->id}"
        ], 200);
    }

    // List All Posts
    public function index(Request $request)
    {
        $limit = $request->input('limit', 10);
        $page = $request->input('page', 1);
        $status = $request->input('status', null);
        $userId = $request->input('user_id', null);
        
        $query = Post::query();
        
        if ($status) {
            $query->where('status', $status);
        }
        
        if ($userId) {
            $query->where('user_id', $userId);
        }

        $posts = $query->with('user')
            ->skip(($page - 1) * $limit)
            ->take($limit)
            ->get();

        $totalCount = $query->count();
        $lastPage = ceil($totalCount / $limit);

        $data = [];
        foreach ($posts as $post) {
            $data[] = [
                'id' => $post->id,
                'title' => $post->title,
                'status' => $post->status,
                'content' => $post->content,
                'user_id' => $post->user_id,
                'user' => [
                    'id' => $post->user->id,
                    'name' => $post->user->name
                ],
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
