<?php

use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\WelcomeController;

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

Route::get('/', [WelcomeController::class, 'index'])->name('main');
Route::get('login', [LoginController::class, 'redirectToDiscord'])->name('login.index');
Route::get('login/callback', [LoginController::class, 'handleDiscordCallback'])
    ->name('login.callback');
Route::get('logout', [LoginController::class, 'logout'])->name('login.logout');

Route::group(['middleware' => 'auth'], function () {
    Route::get('/dashboard', [\App\Http\Livewire\Dashboard::class])->name('dashboard');
});

Route::group(['prefix' => 'admin', 'middleware' => ['admin']], function() {
    Route::get('users', 'UserController@index')->name('admin.user.index');
});
