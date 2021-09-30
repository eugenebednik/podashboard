<?php

use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\WelcomeController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Admin\RoleController;
use App\Http\Controllers\Admin\WebhookController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('welcome', [WelcomeController::class, 'welcome'])->name('welcome');
Route::get('server-required', [WelcomeController::class, 'serverRequired'])->name('server-required');
Route::get('inactive', [WelcomeController::class, 'inactive'])->name('inactive');
Route::get('login/callback', [LoginController::class, 'handleDiscordCallback'])->name('login.callback');

Route::group(['middleware' => 'server_active'], function () {
    Route::get('/', [WelcomeController::class, 'index'])->name('main')->middleware('server_active');
    Route::get('login', [LoginController::class, 'redirectToDiscord'])->name('login.index')->middleware('server_active');
    Route::get('logout', [LoginController::class, 'logout'])->name('login.logout')->middleware('server_active');
});

Route::group(['middleware' => ['session_exists', 'auth', 'in_guild']], function () {
    Route::get('dashboard/fulfill', [DashboardController::class, 'fulfill'])->name('dashboard.fulfill');
    Route::get('dashboard', [DashboardController::class, 'index'])->name('dashboard');

    Route::group(['prefix' => 'admin', 'middleware' => ['admin']], function() {
        Route::get('users', [UserController::class, 'index'])->name('admin.user.index');
        Route::get('roles', [RoleController::class, 'index'])->name('admin.roles.index');
        Route::get('webhooks', [WebhookController::class, 'index'])->name('admin.webhooks.index');
    });
});
