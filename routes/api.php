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
	Route::post('filter','productsController@filter'); //OK
	Route::get('show/{id}','productsController@show'); //OK
	Route::post('edit/{id}','productsController@edit');//OK
	Route::post('update/{id}','productsController@update');//OK
	
	Route::get('sales/{id}','productsSaleController@index'); //OK
	Route::get('transactions/{id}','productsTransController@index'); //OK
	Route::post('store','productsController@store'); //OK
	Route::post('delete','productsController@destroy');//OK
	Route::post('active','productsController@update');//OK

	Route::post('query','productsController@query');//OK
	Route::post('search','productsController@search');//OK

});
Route::group(['prefix' => 'rest/v1/inventories'],function(){//done
	Route::get('/','inventoriesController@index'); //OK
	Route::post('query','inventoriesController@query');	//OK
});
Route::group(['prefix' => 'rest/v1/transactions'],function(){
	Route::get('/','transactionsController@index'); //OK
	Route::get('show/{id}','transactionsController@show'); //OK
	Route::post('update/{id}','transactionsController@update');//OK
	Route::post('store','transactionsController@store'); //OK
	Route::post('search','transactionsController@search');//OK
	Route::post('searchProduct','transactionsController@searchProduct');//OK
});
Route::group(['prefix' => 'rest/v1/invoices'],function(){
	Route::get('/','invoicesController@index');//OK
	Route::get('show/{id}','invoiceController@index');//OK
	Route::post('update/{id}','invoiceController@update');//OK
	Route::post('filter','invoiceFilter@index');
});
Route::group(['prefix' => 'rest/v1/statistic'],function(){
	Route::get('/','statisticController@index');//OK
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
	Route::post('insert','AddInvoiceController@postInvoice');
	Route::get('saleman/{id}','SalemanController@index');
	Route::get('saleman','UserController@index');
	Route::post('edit_customer/{id}','EditCustomerController@edit');
});

	
