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
    if (!Auth::check()) {
        return view('/auth/login');
    } else {
        return view('home');
    }
});

Auth::routes();
Route::get('/home', 'HomeController@index')->name('home');

Route::group(['middleware' => 'auth'], function() {
    Route::prefix('users')->group(function () {
        Route::get('/list', 'UsersController@index');
        Route::post('/list', 'UsersController@index')->name('filters');
        Route::post('/list/add', 'UsersController@add');
        Route::post('/list/update', 'UsersController@edit');
        Route::post('/list/remove', 'UsersController@remove');
    });

    Route::get('/profile/details', 'HomeController@profileDetails');
    Route::post('/profile/updateProfile', 'HomeController@editProfile');
});
