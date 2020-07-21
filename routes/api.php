<?php

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


Route::group(['middleware' => 'auth:api'], function () {
    Route::apiResource('request-types', 'Api\RequestTypesApiController', ['only' => ['index']]);
    Route::apiResource('buff-requests/done', 'Api\DoneApiController', ['only' => ['store']]);
    Route::apiResource('buff-requests', 'Api\BuffRequestApiController');

    Route::group(['middleware' => 'admin'], function () {
        Route::apiResource('users', 'Api\UsersApiController', ['except' => ['create', 'edit']]);
        Route::apiResource('alliances', 'Api\AlliancesApiController', ['except' => ['create', 'edit']]);
    });
});