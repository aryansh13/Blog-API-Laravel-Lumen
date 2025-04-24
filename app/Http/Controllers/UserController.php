<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    // Create User
    public function store(Request $request)
    {
        $this->validate($request, [
            'name' => 'required|max:100',
            'email' => 'required|email|max:50|unique:users',
            'password' => 'required|min:6|max:50'
        ]);

        $user = new User();
        $user->name = $request->name;
        $user->email = $request->email;
        $user->password = Hash::make($request->password);
        $user->save();

        return response()->json([
            'id' => $user->id,
            'name' => $user->name,
            'email' => $user->email,
            'link' => "/users/{$user->id}"
        ], 201);
    }

    // Get Single User
    public function show($id)
    {
        $user = User::with(['posts', 'comments'])->find($id);

        if (!$user) {
            return response()->json(['message' => 'User not found'], 404);
        }

        $postsCount = $user->posts->count();
        $commentsCount = $user->comments->count();

        return response()->json([
            'id' => $user->id,
            'name' => $user->name,
            'email' => $user->email,
            'stats' => [
                'posts_count' => $postsCount,
                'comments_count' => $commentsCount
            ],
            'link' => "/users/{$user->id}"
        ], 200);
    }

    // Update User
    public function update(Request $request, $id)
    {
        $user = User::find($id);

        if (!$user) {
            return response()->json(['message' => 'User not found'], 404);
        }

        $this->validate($request, [
            'name' => 'sometimes|max:100',
            'email' => 'sometimes|email|max:50|unique:users,email,' . $id,
            'password' => 'sometimes|min:6|max:50'
        ]);

        if ($request->has('name')) {
            $user->name = $request->name;
        }

        if ($request->has('email')) {
            $user->email = $request->email;
        }

        if ($request->has('password')) {
            $user->password = Hash::make($request->password);
        }

        $user->save();

        return response()->json([
            'id' => $user->id,
            'name' => $user->name,
            'email' => $user->email,
            'link' => "/users/{$user->id}"
        ], 200);
    }

    // Delete User
    public function destroy($id)
    {
        $user = User::find($id);

        if (!$user) {
            return response()->json(['message' => 'User not found'], 404);
        }

        $user->delete();

        return response()->json([
            'success' => true,
            'deleted_id' => $id
        ], 200);
    }

    // List All Users
    public function index(Request $request)
    {
        $limit = $request->input('limit', 10);
        $page = $request->input('page', 1);
        $search = $request->input('search', null);

        $query = User::query();
        
        if ($search) {
            $query->where('name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%");
        }

        $users = $query->withCount(['posts', 'comments'])
            ->skip(($page - 1) * $limit)
            ->take($limit)
            ->get();

        $totalCount = $query->count();
        $lastPage = ceil($totalCount / $limit);

        $data = [];
        foreach ($users as $user) {
            $data[] = [
                'id' => $user->id,
                'name' => $user->name,
                'email' => $user->email,
                'stats' => [
                    'posts_count' => $user->posts_count,
                    'comments_count' => $user->comments_count
                ],
                'link' => "/users/{$user->id}"
            ];
        }

        return response()->json([
            'data' => $data,
            'total_count' => $totalCount,
            'limit' => $limit,
            'pagination' => [
                'first_page' => "/users?page=1",
                'last_page' => "/users?page={$lastPage}",
                'page' => $page,
                'next_page' => $page < $lastPage ? "/users?page=" . ($page + 1) : null,
                'prev_page' => $page > 1 ? "/users?page=" . ($page - 1) : null
            ]
        ], 200);
    }
} 