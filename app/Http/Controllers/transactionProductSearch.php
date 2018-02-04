<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class transactionProductSearch extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        //
        $transactionProduct = $request->input('transaction_product');
        $transaction_product_name = $transactionProduct->transaction_product_name;
        $transaction_product_barcode = $transactionProduct->transaction_product_barcode;
        $transaction_product_stock_number = $transactionProduct->transaction_product_stock_number;
        $product = Products::where([
            ['product_name','LIKE','%'.$transaction_product_name.'%'],
            ['product_barcode','LIKE','%'.$transaction_product_barcode.'%'],
            ['product_stock_number','LIKE','%'.$transaction_product_stock_number.'%']
        ])->get();
        return respones()->json($this->transformCollection($product),200);
    }
    public function transformCollection($product) {

    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
