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
        $productName = $request->input('product_name')?$request->input('product_name'):'';
        $productName = strtolower($productName);
        if ($productName !== ''){
            $products = Products::orderBy('product_id','desc')
            ->where('product_name','LIKE','%'.$productName.'%')
            ->get();
            return response()->json($this->transformCollection($products),200);
        }
        else {
             $products = Products::orderBy('product_id','desc')
            ->get();
            return response()->json($this->transformCollection($products),200);
        }

        
       
    }
    public function transformCollection($products) {
        //Chuyển truy vấn dạng object thành mảng
        $productsToArray = $products->toArray();
        return [    
            'status' => 0,
            'messages' => 'Return success!',
            'data' => array_map([$this,'transformData'],$productsToArray)
        ];
    }
    public function transformData($products) {
        //$show = json_decode(json_encode($products));
        //Trả về định dạng cho dữ liệu
        return [
            'product_id' => $products['product_id'],
            'product_stock_number' => $products['product_stock_number'],
            'product_name' => $products['product_name'],
            'product_unit_string' => $products['product_unit_string'],
            'product_unit_quantity' => $products['product_unit_quantity'],
            'product_on_hand' => $products['product_on_hand'],
            'product_retail_price' => $products['product_retail_price']
        ];
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
