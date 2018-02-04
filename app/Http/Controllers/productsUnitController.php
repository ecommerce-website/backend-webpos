<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class productsUnitController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
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
    public function store(Request $request,$id)
    {
        //
        $productUnit = $request->input('product_unit');
        $productUnitArray = explode('-', $productUnit);
        $getProduct = Products::where('product_id',$id)->get()->toArray();
        $product = new Products();

        $product->product_type = 'Unit product';
        $product->product_unit_string = $productUnitArray[0];
        $product->product_unit_quantity = (int)$productUnitArray[1];
        $product->product_stock_number = $getProduct['product_stock_number'].'-'.$product->product_unit_string;
        $product->product_retail_price = $getProduct['product_retail_price'] * $product->product_unit_quantity;
        $product->product_on_hand = 0;
        $product->product_description = $getProduct['product_description'];
        $product->product_name = $getProduct['product_name'];
        $product->prouct_cost = $getProduct['product_cost'] * $product->product_unit_quantity;
        $product->product_min_quantity = $getProduct['product_min_quantity'];
        $product->product_max_quantity = $getProduct['product_max_quantity'];

        $product->save();
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
