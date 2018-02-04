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
Route::group(['prefix' => 'rest/v1/inventories'],function(){//done
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

Route::group(['prefix' => 'rest/v1'], function(){
	// Route::put('jokes/{id}', 'JokesController@update');
	Route::resource('salesman','UserController');

});



Route::group(['prefix' => 'rest/v1'], function(){
	
	Route::get('salesman','UserController@index');

	Route::get('customer','CustomerController@index');

	Route::post('customer_delete','CustomerController@destroy');

	Route::post('customer/add','CustomerController@store');
	Route::get('customer/{id}','EditCustomerController@index');
	Route::get('customer_search','SearchCustomerController@index');
});

	
