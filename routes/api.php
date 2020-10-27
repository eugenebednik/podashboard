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
    Route::apiResource('requests/count', 'Api\MyCompletedRequestsApiController', ['only' => ['show']]);
    Route::apiResource('requests/done', 'Api\DoneApiController', ['only' => ['store']]);
    Route::apiResource('requests/fulfill', 'Api\FulfillRequestApiController', ['only' => 'update']);
    Route::apiResource('requests', 'Api\BuffRequestApiController');
    Route::apiResource('server', 'Api\ServerApiController', ['only' => ['store']]);

    Route::group(['prefix' => 'admin', 'middleware' => 'admin.api'], function () {
        Route::apiResource('roles', 'Api\Admin\RoleApiController', ['only' => ['update']]);
        Route::apiResource('server', 'Api\Admin\ServerApiController', ['only' => ['show']]);
    });
});
