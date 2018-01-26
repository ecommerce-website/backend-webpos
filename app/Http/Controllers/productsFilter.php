<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class productsFilter extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        //
        $productsType = $request->input('type');
        $productsStatus = $request->input('status');
        $productsTags = $request->input('tag');
        $products = Products::with(array(
            'barcodes',
            'qltags' => function($query){
                $query->with('tags');
            }
        ))
        ->select(
            'product_id',
            'product_type',
            'product_stock_number',
            'product_name',
            'product_img',
            'product_unit_string',
            'product_unit_quantity',
            'product_description',
            'product_active',
            'product_on_hand',
            'product_retail_price'
        )
        ->orderBy('product_id','asc')
        ->get();
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
