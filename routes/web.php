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
    return view('welcome');
});

Auth::routes();

Route::get('auth/google', 'Auth\RegisterController@redirectToProvider')->name('auth.google');
Route::get('auth/google/callback', 'Auth\RegisterController@handleProviderCallback')->name('auth.google.callback');


Route::middleware(['web', 'auth'])->group(function () {
	Route::get('home', 'HomeController@index')->name('home');

	Route::post('fetch', 'GoogleDriverController@fetch')->name('gd.files.fetch');
	Route::get('files', 'GoogleDriverController@index')->name('gd.files.index');
});
