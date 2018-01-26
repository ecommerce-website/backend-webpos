<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Products;

class inventoriesFilter extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        //
        $name = $request->input('name')?$request->input('name'):'';
        if ($name !== ''){
            $products = Products::select(
                'product_id',
                'product_stock_number',
                'product_name',
                'product_unit_string',
                'product_unit_quantity',
                'product_on_hand',
                'product_retail_price'
            )
            ->where('product_name','LIKE','%$name%')
            ->get();
        }
        else {
             $products = Products::select(
                'product_id',
                'product_stock_number',
                'product_name',
                'product_unit_string',
                'product_unit_quantity',
                'product_on_hand',
                'product_retail_price'
            )
            ->get();
        }
        return response()->json($this->transformCollection($products),200);
       
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
