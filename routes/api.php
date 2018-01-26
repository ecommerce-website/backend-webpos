<?php

use Illuminate\Http\Request;

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

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});
Route::group(['prefix' => 'rest/v1'],function() {
	Route::get('products','productsController@index');
	Route::get('products/sales/{id}','productsSaleController@index');
	Route::get('products/transactions/{id}','productsTransController@index');
	Route::get('products/filter','productsFilter@index');
	Route::get('inventories','inventoriesController@index');
	Route::get('inventories/filter','inventoriesFilter@index');
});

