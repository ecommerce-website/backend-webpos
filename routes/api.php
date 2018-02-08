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
	Route::get('/','productsController@index'); //OK
	Route::post('filter','productsFilter@index'); //OK
	Route::get('show/{id}','productController@index'); //OK
	Route::post('edit/{id}','productController@edit');
	Route::post('update/{id}','productController@update');
	
	Route::get('sales/{id}','productsSaleController@index'); //OK
	Route::get('transactions/{id}','productsTransController@index'); //OK
	Route::post('storeUnit/{id}','productsUnitController@store');
	Route::post('store','productsController@store'); //OK
	Route::post('delete','productsController@destroy');
	Route::post('active','productsController@update');

	Route::post('query','productBarcodeSearch@query');
	Route::post('search','productBarcodeSearch@search');

});
Route::group(['prefix' => 'rest/v1/inventories'],function(){//done
	Route::get('/','inventoriesController@index');
	Route::get('filter','inventoriesFilter@index');
});
Route::group(['prefix' => 'rest/v1/transactions'],function(){
	Route::get('/','transactionsController@index');
	Route::get('show/{id}','transactionController@index');
	Route::post('update/{id}','transactionController@edit');
	Route::post('store','transactionController@store');
	Route::get('transactionType','transactionTypeController@index');
});
Route::group(['prefix' => 'rest/v1/invoices'],function(){
	Route::get('/','invoicesController@index');
	Route::get('show/{id}','invoiceController@index');
	Route::post('update/{id}','invoiceController@update');
	Route::post('filter','invoiceFilter@index');
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

	
