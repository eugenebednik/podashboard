<?php

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

Route::get('/', function () {
    return redirect('/dashboard');
});

Auth::routes();

Route::group(['prefix' => 'admin', 'middleware' => ['admin']], function() {
    Route::get('users', 'UserController@index')->name('admin.user.index');
    Route::get('alliances', 'AllianceController@index')->name('admin.alliance.index');
});

Route::get('/dashboard', 'DashboardController@index')->name('dashboard');
Route::get('/inactive', 'InactiveController@index')->name('inactive');
Route::get('/fulfill/{id}', 'DashboardController@fulfill')->name('fulfill');