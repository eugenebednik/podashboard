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

Route::group(['middleware' => 'auth:api', 'namespace' => 'Api'], function () {
    Route::apiResource('server', 'ServerApiController', ['only' => ['show', 'store', 'update']]);
    Route::apiResource('queue', 'QueueApiController', ['only' => ['show']]);
    Route::apiResource('bot-requests', 'BotRequestsApiController', ['only' => ['store', 'update', 'destroy']]);
    Route::apiResource('bot-requests/done', 'DoneApiController', ['only' => ['store']]);

    Route::group(['middleware' => 'server_id'], function () {
        Route::apiResource('request-types', 'RequestTypesApiController', ['only' => ['index']]);
        Route::apiResource('requests', 'BuffRequestApiController', ['only' => ['index', 'show']]);
        Route::apiResource('requests/count', 'MyCompletedRequestsApiController', ['only' => ['show']]);
        Route::apiResource('requests/fulfill', 'FulfillRequestApiController', ['only' => 'update']);
        Route::apiResource('on-duty', 'OnDutyApiController', ['only' => ['update']]);

        Route::group(['prefix' => 'admin', 'middleware' => 'admin.api', 'namespace' => 'Admin'], function () {
            Route::apiResource('roles', 'RoleApiController', ['only' => ['update']]);
            Route::apiResource('server', 'ServerApiController', [
                'as' => 'server-admin',
                'only' => ['show']
            ]);
            Route::apiResource('users', 'UserAdminApiController', ['only' => ['show', 'update']]);
        });
    });
});
