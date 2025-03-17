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

$router->group(['prefix' => 'posts'], function () use ($router) {
    $router->post('/', 'PostController@store');
    $router->get('/', 'PostController@index');
    $router->get('/{id}', 'PostController@show');
    $router->patch('/{id}', 'PostController@update');
    $router->delete('/{id}', 'PostController@destroy');
    
    // Comment routes nested under posts
    $router->post('/{postId}/comments', 'CommentController@store');
    $router->get('/{postId}/comments', 'CommentController@index');
});

// Comment routes
$router->group(['prefix' => 'comments'], function () use ($router) {
    $router->get('/{id}', 'CommentController@show');
    $router->patch('/{id}', 'CommentController@update');
    $router->delete('/{id}', 'CommentController@destroy');
});
