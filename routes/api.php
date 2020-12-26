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
    Route::apiResource('server', 'Api\ServerApiController', ['only' => ['show', 'store', 'update']]);
    Route::apiResource('queue', 'Api\QueueApiController', ['only' => ['show']]);
    Route::apiResource('bot-requests', 'Api\BotRequestsApiController', ['only' => ['store', 'update', 'destroy']]);
    Route::apiResource('bot-requests/done', 'Api\DoneApiController', ['only' => ['store']]);

    Route::group(['middleware' => 'server_id'], function () {
        Route::apiResource('request-types', 'Api\RequestTypesApiController', ['only' => ['index']]);
        Route::apiResource('requests', 'Api\BuffRequestApiController', ['only' => ['index', 'show']]);
        Route::apiResource('requests/count', 'Api\MyCompletedRequestsApiController', ['only' => ['show']]);
        Route::apiResource('requests/fulfill', 'Api\FulfillRequestApiController', ['only' => 'update']);
        Route::apiResource('on-duty', 'Api\OnDutyApiController', ['only' => ['update']]);

        Route::group(['prefix' => 'admin', 'middleware' => 'admin.api'], function () {
            Route::apiResource('roles', 'Api\Admin\RoleApiController', ['only' => ['update']]);
            Route::apiResource('server', 'Api\Admin\ServerApiController', ['only' => ['show']]);
        });
    });
});
