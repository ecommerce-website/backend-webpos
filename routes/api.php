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
	Route::get('products/show/{id}','productController@index');
	Route::post('products/store/{id}','productController@store');
	Route::get('products/edit/{id}','productController@edit');

	Route::get('products/sales/{id}','productsSaleController@index');
	Route::get('products/transactions/{id}','productsTransController@index');

	Route::get('products','productsController@index');
	Route::post('products/store','productsController@store');
	Route::post('products/delete','productsController@destroy');
	Route::post('products/active','productsController@update');

	Route::post('products/storeUnit/{id}','productsUnitController@store');

	Route::get('products/filter','productsFilter@index');

	Route::get('inventories','inventoriesController@index');
	Route::get('inventories/filter','inventoriesFilter@index');

	Route::get('transactions','transactionsController@index');

	Route::get('transactions/show/{id}','transactionController@index');
	Route::get('transactions/update/{id}','transactionController@edit');
	Route::get('transactions/store','transactionController@store');

	Route::get('transactionsType','transactionTypeController@index');

	Route::get('invoices','invoicesController@index');

	Route::get('invoices/show/{id}','invoiceController@index');
	Route::get('invoices/update/{id}','invoiceController@update');
});

