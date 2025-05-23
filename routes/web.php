<?php

/** @var \Laravel\Lumen\Routing\Router $router */

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It is a breeze. Simply tell Lumen the URIs it should respond to
| and give it the Closure to call when that URI is requested.
|
*/

// $router->get('/', function () use ($router) {
//     return $router->app->version();
// });

// Auth Routes
$router->group(['prefix' => 'auth'], function () use ($router) {
    $router->post('/register', 'AuthController@register');
    $router->post('/login', 'AuthController@login');
    $router->post('/logout', ['middleware' => 'auth:api', 'uses' => 'AuthController@logout']);
    
    // Protected auth routes
    $router->group(['middleware' => 'auth:api'], function () use ($router) {
        $router->get('/me', 'AuthController@me');
    });
});

// Legacy User routes - can be removed after migration to auth system
$router->group(['prefix' => 'users'], function () use ($router) {
    $router->get('/', 'UserController@index');
    $router->get('/{id}', 'UserController@show');
    $router->patch('/{id}', 'UserController@update');
    $router->delete('/{id}', 'UserController@destroy');
});

// Protected Post routes
$router->group(['prefix' => 'posts', 'middleware' => 'auth:api'], function () use ($router) {
    // Penulis routes - POST
    $router->group(['middleware' => 'role:penulis'], function () use ($router) {
        $router->post('/', 'PostController@store');
    });
    
    // Editor routes - PUT
    $router->group(['middleware' => 'role:editor'], function () use ($router) {
        $router->put('/{id}', 'PostController@update');
    });
    
    // Admin routes - DELETE
    $router->group(['middleware' => 'role:admin'], function () use ($router) {
        $router->delete('/{id}', 'PostController@destroy');
    });
    
    // Protected Comment routes nested under posts
    $router->group(['middleware' => 'role:penulis'], function () use ($router) {
        $router->post('/{postId}/comments', 'CommentController@store');
    });
});

// Public Post routes (read-only)
$router->group(['prefix' => 'posts'], function () use ($router) {
    $router->get('/', 'PostController@index');
    $router->get('/{id}', 'PostController@show');
    $router->get('/{postId}/comments', 'CommentController@index');
});

// Protected Comment routes
$router->group(['prefix' => 'comments', 'middleware' => 'auth:api'], function () use ($router) {
    // Editor routes - PUT
    $router->group(['middleware' => 'role:editor'], function () use ($router) {
        $router->put('/{id}', 'CommentController@update');
    });
    
    // Admin routes - DELETE
    $router->group(['middleware' => 'role:admin'], function () use ($router) {
        $router->delete('/{id}', 'CommentController@destroy');
    });
});

// Public Comment routes (read-only)
$router->group(['prefix' => 'comments'], function () use ($router) {
    $router->get('/{id}', 'CommentController@show');
});

// Admin routes
$router->group(['prefix' => 'admin', 'middleware' => ['auth:api', 'role:admin']], function () use ($router) {
    $router->get('/users', 'UserController@index');
    $router->get('/users/{id}', 'UserController@show');
    $router->put('/users/{id}', 'UserController@update');
    $router->delete('/users/{id}', 'UserController@destroy');
});
