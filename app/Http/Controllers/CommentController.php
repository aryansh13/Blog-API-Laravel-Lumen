<?php

namespace App\Http\Controllers;

use App\Models\Comment;
use App\Models\Post;
use App\Models\User;
use Illuminate\Http\Request;

class CommentController extends Controller
{
    // Create Comment
    public function store(Request $request, $postId)
    {
        $post = Post::find($postId);

        if (!$post) {
            return response()->json(['message' => 'Post not found'], 404);
        }

        $this->validate($request, [
            'comment' => 'required|max:250',
            'user_id' => 'required|exists:users,id'
        ]);

        $comment = new Comment();
        $comment->comment = $request->comment;
        $comment->post_id = $postId;
        $comment->user_id = $request->user_id;
        $comment->save();

        return response()->json([
            'id' => $comment->id,
            'post_id' => $comment->post_id,
            'user_id' => $comment->user_id,
            'comment' => $comment->comment,
            'link' => "/comments/{$comment->id}"
        ], 201);
    }

    // Get Single Comment
    public function show($id)
    {
        $comment = Comment::with(['user', 'post'])->find($id);

        if (!$comment) {
            return response()->json(['message' => 'Comment not found'], 404);
        }

        return response()->json([
            'id' => $comment->id,
            'post_id' => $comment->post_id,
            'user_id' => $comment->user_id,
            'comment' => $comment->comment,
            'user' => [
                'id' => $comment->user->id,
                'name' => $comment->user->name
            ],
            'post' => [
                'id' => $comment->post->id,
                'title' => $comment->post->title
            ],
            'link' => "/comments/{$comment->id}"
        ], 200);
    }

    // Update Comment
    public function update(Request $request, $id)
    {
        $comment = Comment::find($id);

        if (!$comment) {
            return response()->json(['message' => 'Comment not found'], 404);
        }

        $this->validate($request, [
            'comment' => 'sometimes|max:250',
            'user_id' => 'sometimes|exists:users,id'
        ]);

        if ($request->has('comment')) {
            $comment->comment = $request->comment;
        }

        if ($request->has('user_id')) {
            $comment->user_id = $request->user_id;
        }

        $comment->save();

        return response()->json([
            'id' => $comment->id,
            'post_id' => $comment->post_id,
            'user_id' => $comment->user_id,
            'comment' => $comment->comment,
            'link' => "/comments/{$comment->id}"
        ], 200);
    }

    // Delete Comment
    public function destroy($id)
    {
        $comment = Comment::find($id);

        if (!$comment) {
            return response()->json(['message' => 'Comment not found'], 404);
        }

        $comment->delete();

        return response()->json([
            'success' => true,
            'deleted_id' => $id
        ], 200);
    }

    // List Post Comments
    public function index($postId, Request $request)
    {
        $post = Post::find($postId);

        if (!$post) {
            return response()->json(['message' => 'Post not found'], 404);
        }

        $limit = $request->input('limit', 10);
        $page = $request->input('page', 1);
        $userId = $request->input('user_id', null);

        $query = Comment::where('post_id', $postId);
        
        if ($userId) {
            $query->where('user_id', $userId);
        }

        $comments = $query->with('user')
            ->skip(($page - 1) * $limit)
            ->take($limit)
            ->get();

        $totalCount = $query->count();
        $lastPage = ceil($totalCount / $limit);

        $data = [];
        foreach ($comments as $comment) {
            $data[] = [
                'id' => $comment->id,
                'post_id' => $comment->post_id,
                'user_id' => $comment->user_id,
                'comment' => $comment->comment,
                'user' => [
                    'id' => $comment->user->id,
                    'name' => $comment->user->name
                ],
                'link' => "/comments/{$comment->id}"
            ];
        }

        return response()->json([
            'data' => $data,
            'total_count' => $totalCount,
            'limit' => $limit,
            'pagination' => [
                'first_page' => "/posts/{$postId}/comments?page=1",
                'last_page' => "/posts/{$postId}/comments?page={$lastPage}",
                'page' => $page,
                'next_page' => $page < $lastPage ? "/posts/{$postId}/comments?page=" . ($page + 1) : null,
                'prev_page' => $page > 1 ? "/posts/{$postId}/comments?page=" . ($page - 1) : null
            ]
        ], 200);
    }
}
