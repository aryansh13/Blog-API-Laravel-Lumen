<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class CheckRole
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @param  string  $role
     * @return mixed
     */
    public function handle(Request $request, Closure $next, $role)
    {
        if (!$request->user()) {
            return response()->json(['message' => 'Unauthorized'], 401);
        }

        $user = $request->user();
        
        // Admin memiliki akses ke semua
        if ($user->isAdmin()) {
            return $next($request);
        }
        
        switch ($role) {
            case 'penulis':
                if (!$user->isPenulis() && !$user->isAdmin()) {
                    return response()->json(['message' => 'Access denied. Penulis role required.'], 403);
                }
                break;
            case 'editor':
                if (!$user->isEditor() && !$user->isAdmin()) {
                    return response()->json(['message' => 'Access denied. Editor role required.'], 403);
                }
                break;
            case 'pembaca':
                if (!$user->isPembaca() && !$user->isAdmin()) {
                    return response()->json(['message' => 'Access denied. Pembaca role required.'], 403);
                }
                break;
            default:
                return response()->json(['message' => 'Invalid role specified'], 400);
        }

        return $next($request);
    }
} 