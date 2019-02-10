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

	if(\Auth::check()){
		return view('admin.index');
	} 
	return view('auth.index');
    
})->name('index');
Route::get('/forgotpassword', 'Auth\ForgotPasswordController@index')->name('forgotpassword');
Route::group(['middleware' => 'isLogin'], function() {
	Route::get('/products','Admin\Products@index')->name('products');
	Route::get('/categories', 'Admin\Categories@index')->name('categories');
	Route::get('/pos', 'Admin\POS@index')->name('pos');
	Route::get('/users', 'Admin\Users@index')->name('users');
});
