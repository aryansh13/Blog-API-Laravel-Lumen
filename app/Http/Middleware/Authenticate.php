<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Contracts\Auth\Factory as Auth;
use Laravel\Sanctum\TransientToken;
use Illuminate\Support\Carbon;

class Authenticate
{
    /**
     * The authentication guard factory instance.
     *
     * @var \Illuminate\Contracts\Auth\Factory
     */
    protected $auth;

    /**
     * Create a new middleware instance.
     *
     * @param  \Illuminate\Contracts\Auth\Factory  $auth
     * @return void
     */
    public function __construct(Auth $auth)
    {
        $this->auth = $auth;
    }

    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @param  string|null  $guard
     * @return mixed
     */
    public function handle($request, Closure $next, $guard = null)
    {
        if ($request->bearerToken()) {
            $token = $request->bearerToken();
            $tokenId = explode('|', $token)[0];
            
            $tokenModel = \Laravel\Sanctum\PersonalAccessToken::find($tokenId);
            
            if ($tokenModel && (!$tokenModel->expires_at || $tokenModel->expires_at > Carbon::now())) {
                $user = $tokenModel->tokenable;
                if ($user) {
                    $this->auth->guard($guard)->setUser($user);
                    return $next($request);
                }
            }
        }

        return response()->json([
            'message' => 'Unauthorized. Invalid or expired token.',
            'status' => 401
        ], 401);
    }
}
