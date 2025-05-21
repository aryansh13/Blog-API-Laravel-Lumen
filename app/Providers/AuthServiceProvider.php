<?php

namespace App\Providers;

use App\Models\User;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\ServiceProvider;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Boot the authentication services for the application.
     *
     * @return void
     */
    public function boot()
    {
        // Here you may define how you wish users to be authenticated for your Lumen
        // application. The callback which receives the incoming request instance
        // should return either a User instance or null. You're free to obtain
        // the User instance via an API token or any other method necessary.

        $this->app['auth']->viaRequest('api', function ($request) {
            $token = $request->header('Authorization');
            
            // Check if token exists in header
            if ($token) {
                // Remove "Bearer " prefix if it exists
                if (strpos($token, 'Bearer ') === 0) {
                    $token = substr($token, 7);
                }
                
                return User::where('api_token', $token)->first();
            }
            
            // Check if token exists as input parameter
            if ($request->input('api_token')) {
                return User::where('api_token', $request->input('api_token'))->first();
            }
            
            return null;
        });
    }
}
