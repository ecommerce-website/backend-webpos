<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Products;

class productsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        //
        $limit = $request->input('limit')?$request->input('limit'):8;
        $products = Products::orderBy('product_id','ASC')
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
        ->paginate($limit);
        $products->product_barcodes = array();
        foreach ($products as $value){
            $data = $value
                    ->barcodes
                    ->where($value->product_id,'=',$value->barcode_product_id);
            foreach ($data as $barcodes) {
                # code...
                echo "<pre>";
                var_dump($barcodes->toArray());
                echo "</pre>";
            }
        }
        // $this->transformCollection($products);
        // return response()->json($this->transformCollection($products),200);
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
        Products::destroy($id);
    }
    public function transformCollection($products) {
        $productsToArray = $products->toArray();
        // return [    
        //     'current_page' => $productsToArray['current_page'],
        //     'first_page_url' => $productsToArray['first_page_url'],
        //     'last_page_url' => $productsToArray['last_page_url'],
        //     'next_page_url' => $productsToArray['next_page_url'],
        //     'prev_page_url' => $productsToArray['prev_page_url'],
        //     'per_page' => $productsToArray['per_page'],
        //     'from' => $productsToArray['from'],
        //     'to' => $productsToArray['to'],
        //     'total' => $productsToArray['total'],
        //     'data' => array_map([$this,'transformData'],$productsToArray['data']);

        // ];
        // echo "<pre>";
        // print_r($products);
        // echo "</pre>";
    }
}
