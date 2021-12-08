<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::group(['middleware' => 'api', 'prefix' => 'auth'], function ($router) {
    Route::post('login', 'AuthController@login');
    Route::post('logout', 'AuthController@logout');
    Route::post('refresh', 'AuthController@refresh');
    Route::post('me', 'AuthController@me');
});

Route::group(['prefix' => 'v1', 'middleware' => 'jwt.verify'], (function () {
    Route::apiResources(
        [
            'product' => 'ProductController',
            'category' => 'CategoryController'
        ],
        ['only' => ['store', 'update', 'destroy',]]
    );
}));

Route::group(
    ['prefix' => 'v1'],
    (function () {
        Route::apiResources(
            [
                'product' => 'ProductController',
                'category' => 'CategoryController'
            ],
            ['only' => ['index', 'show']]
        );
    }),
);

Route::group(['prefix' => 'v1'], (function () {
    Route::apiResources(['user' => 'UserController'], ['only' => ['store']]);
}));

Route::group(['prefix' => 'v1', 'middleware' => 'jwt.verify'], (function () {
    Route::apiResources(
        [
            'user' => 'UserController', 
            'cart' => 'CartItemController'
        ],
        ['only' => ['index', 'show', 'update', 'destroy']]
    );
}));

Route::get('/status', function () {
    return ['status' => 'OK'];
});
