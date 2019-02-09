<?php

use Illuminate\Http\Request;


// Route::middleware('auth:api')->get('/user', function (Request $request) {
//     return $request->user();
// });
Route::post('/auth', 'Auth\LoginController@auth')->name('api.login');
Route::get('/logout', 'Auth\LoginController@logout')->name('api.logout');

Route::group(['middleware' => 'isLogin'], function(){
	//products
	Route::get('/products', 'Admin\Products@datatable')->name('api.products');
	Route::post('/product/create', 'Admin\Products@create')->name('api.product-create');
	Route::get('/product/{id}', 'Admin\Products@read')->name('api.product-read');
	Route::put('/product/{id}', 'Admin\Products@update')->name('api.product-update');
	Route::delete('/product/{id}', 'Admin\Products@delete')->name('api.product-delete');

	//category

	Route::get('/categories','Admin\Categories@datatable')->name('api.cat');
	Route::post('/categories/create', 'Admin\Categories@create')->name('api.cat-create');
	Route::get('/categories/{id}', 'Admin\Categories@read')->name('api.cat-read');
	Route::put('/categories/{id}', 'Admin\Categories@update')->name('api.cat-update');
	Route::delete('/categories/{id}', 'Admin\Categories@delete')->name('api.cat-delete');
});