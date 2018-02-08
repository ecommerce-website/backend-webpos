<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Products;
use DB;

class productBarcodeSearch extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function query(Request $request)
    {
        //
        $search = $request->input('query');
        if ($search === "") {
            $product = Products::with(
                array(
                    'barcodes',
                    'qltags'=>function($query) {
                        $query->with('tags');
                    }
                )
            )
            ->paginate(10);
            return response()->json($this->transformCollection($product),200);
        }
        else {
            $products = DB::table('products')
                        ->join('barcodes', 'products.product_id', '=', 'barcodes.barcode_product_id')
                        ->where('product_name', 'LIKE', '%'.$search.'%')
                        ->orWhere('barcode_name', 'LIKE', '%'.$search.'%')
                        ->paginate(10);
            return response()->json($this->transformCollection($products), 200);
            // $product = Products::whereHas('barcodes',function($query) use ($pro){
            //     $query->where('barcode_name','LIKE','%'.$pro.'%');
            // })
            // ->with(
            //     array(
            //         'barcodes',
            //         'qltags' => function($query) {
            //             $query->with('tags');
            //         }
            //     )
            // )
            // ->where('product_name','LIKE','%'.$pro.'%')
            // ->paginate(10);
            // return response()->json($this->transformCollection($product),200);
        }
       
    }
    public function search(Request $request)
    {
        //
        $barcode_name = $request->input('barcode_name');
        $products = DB::table('products')
                    ->join('barcodes', 'products.product_id', '=', 'barcodes.barcode_product_id')
                    ->where('barcode_name', '=', $barcode_name)
                    ->distinct()->first();
        return response()->json($this->transformData($products), 200);
    }
    public function transformCollection($products) {
        $productsToArray = $products->toArray();
        var_dump($productsToArray);
        die();
        return [    
            'current_page' => $productsToArray['current_page'],
            'first_page_url' => $productsToArray['first_page_url'],
            'last_page_url' => $productsToArray['last_page_url'],
            'next_page_url' => $productsToArray['next_page_url'],
            'prev_page_url' => $productsToArray['prev_page_url'],
            'per_page' => $productsToArray['per_page'],
            'from' => $productsToArray['from'],
            'to' => $productsToArray['to'],
            'total' => $productsToArray['total'],
            'status' => 0,
            'messages' => 'Return success!',
            'data' => array_map([$this,'transformData'],$productsToArray['data'])
        ];
    }
    public function transformData($products) {
        $products = json_decode(json_encode($products), true);
        return [
            'product_id' => $products['product_id'],
            'product_type' => $products['product_type'],
            'product_stock_number' => $products['product_stock_number'],
            'product_name' => $products['product_name'],
            'product_img' => $products['product_img'],
            'product_unit_string' => $products['product_unit_string'],
            'product_unit_quantity' => $products['product_unit_quantity'],
            'product_description' => $products['product_description'],
            'product_active' => $products['product_active'],
            'product_on_hand' => $products['product_on_hand'],
            'product_retail_price' => $products['product_retail_price'],
            'product_barcodes' => $this->collectBarcode($products),
            'product_ql_tags' => []
        ];
    }
    public function collectBarcode($products) {
        //Tạo một mảng để chứa các $barcode của $product
        $arr = [];
        for ($i=0; $i < count($products); $i++) { 
            # code...
            //Tạo một đối tượng $bar để lưu trữ một barcode
            //Một $products sẽ có nhiều $bar
            $bar = new class{};
            $bar->barcode_id = $products['barcode_id'];
            $bar->barcode_product_id = $products['barcode_product_id'];
            $bar->barcode_name = $products['barcode_name'];
            $bar->barcode_img = $products['barcode_img'];
            array_push($arr,$bar);
            break;
        }
        return $arr;
    }
    public function collectQLTag($products) {
        //Tạo một mảng để chứa các $qltag của $product
        $arr = [];
        for ($i = 0;$i < count($products);$i++) {
            //Tạo một đối tượng $qltag để lưu trữ một qltag
            //Một $products sẽ có nhiều $qltag
            $qltag = new class{};
            $qltag->ql_tags_id = $products[$i]['ql_tags_id'];
            $qltag->ql_tags_product_id = $products[$i]['ql_tags_product_id'];
            $qltag->ql_tags_tag_id = $products[$i]['ql_tags_tag_id'];
            $qltag->tags = $this->collectTag($products[$i]['tags']);
            //Thêm đối tượng $qltag
            array_push($arr,$qltag);
        }
        return $arr;
    }
    public function collectTag($tag) {
        //Trả về một đối tượng tag
        $tagObj = new class {};
        $tagObj->tag_id = $tag['tag_id'];
        $tagObj->tag_name = $tag['tag_name'];
        return $tagObj;
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
