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
<<<<<<< HEAD
Route::group(['prefix' => 'rest/v1/products'],function() {
	Route::get('/','productsController@index');
	//Route::get('filter','productsFilter@index');
	Route::get('show/{id}','productController@index');
	// Route::get('edit/{id}','productController@edit');
	Route::get('sales/{id}','productsSaleController@index');
	Route::get('transactions/{id}','productsTransController@index');
	// Route::post('storeUnit/{id}','productsUnitController@store');
	// Route::post('store','productsController@store');
	// Route::post('delete','productsController@destroy');
	// Route::post('active','productsController@update');
});
Route::group(['prefix' => 'rest/v1/inventories'],function(){
	Route::get('/','inventoriesController@index');
	Route::get('filter','inventoriesFilter@index');
});
Route::group(['prefix' => 'rest/v1/transactions'],function(){
	Route::get('/','transactionsController@index');
	Route::get('show/{id}','transactionController@index');
	Route::get('update/{id}','transactionController@edit');
	Route::get('store','transactionController@store');
	Route::get('transactionsType','transactionTypeController@index');
});
Route::group(['prefix' => 'rest/v1/invoices'],function(){
	Route::get('/','invoicesController@index');
	Route::get('show/{id}','invoiceController@index');
	Route::get('update/{id}','invoiceController@update');
	// Route::get('filter','invoicesFilter@index');
});
=======

Route::group(['prefix' => 'rest/v1'], function(){
	// Route::put('jokes/{id}', 'JokesController@update');
	Route::resource('salesman','UserController');
});
	Route::group(['prefix' => 'rest/v1'], function(){
	// Route::put('jokes/{id}', 'JokesController@update');
	Route::resource('customer','CustomerController');
});
	Route::group(['prefix' => 'rest/v1'], function(){
	// Route::put('jokes/{id}', 'JokesController@update');
	Route::resource('customer/delete','CustomerController@destroy');
});
	Route::group(['prefix' => 'rest/v1'], function(){
	// Route::put('jokes/{id}', 'JokesController@update');
	Route::resource('customer/add','CustomerController@store');
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
});


>>>>>>> 7504f2617feb72ed9748b2567538f1a961cdf29e
