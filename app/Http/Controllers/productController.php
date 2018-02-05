<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Products;
use App\Barcodes;
use App\QLTags;
use App\Tags;

class productController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index($id)
    {
        //
        $product = Products::with(array(
            'barcodes',
            'qltags' => function($query) {
                $query->with('tags');
            }
        ))
        ->where('product_id',$id)
        ->get();
        
        return response()->json($this->transformCollection($product),200);
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
    public function edit(Request $request,$id)
    {
        //
        $obj = $request->input('product');
        $product_stock_number = $obj->product_stock_number;
        $product_name = $obj->product_name;
        $product_retail_price = $obj->product_retail_price;
        if ($product_stock_number === '' && $product_name === '' && $product_retail_price === '') {
            return response()->json([
                'error' => [
                    'status' => 1,
                    'message' => 'Hãy cung cấp đủ thông tin'
                ]
            ],422);
        }
        //option
        $product_cost = $obj->product_cost;
        $product_description = $obj->product_description;
        $product_min_quantity = $obj->product_min_quantity;
        $product_max_quantity = $obj->product_max_quantity;
        $product_barcodes = $obj->product_barcodes;
        $product_tags = $obj->product_tags;

        $product_barcodes_toArray = explode(",", $product_barcodes);
        $product_tags_toArray = explode(",", $product_tags);

        $product = Products::where('product_id',$id)->get();
        $barcode = Barcodes::get()->toArray();
        $tag     = Tags::get()->toArray();
        $qltag   = new QLTags();

        $product->product_stock_number = $product_stock_number;
        $product->product_name = $product_name;
        $product->product_retail_price = $product_retail_price;
        $product->product_cost = $product_cost;
        $product->product_description = $product_description;
        $product->product_min_quantity = $product_min_quantity;
        $product->product_max_quantity = $product_max_quantity;
        $product->save();

        $qltag->ql_tags_product_id = $id;
        for ($i = 0;$i < count($product_barcodes_toArray);$i++) {
            $temp = true;
            for ($j = 0;$j < count($barcode);$j++) {
                if ($product_barcodes_toArray[$i] === $barcode[$j]) {
                    $temp = false;
                    break;
                }
            }
            if ($temp) {
                $bar = new Barcodes();
                $bar->barcode_product_id = $id;
                $bar->barcode_name = $product_barcodes_toArray[$i];
                $bar->barcode_img = 'link_img';
                $bar->save();
            }
        }

        for ($i = 0;$i < count($product_tags_toArray);$i++) {
            $temp = true;
            for ($j = 0;$j < count($tag);$j++) {
                if ($product_tags_toArray[$i] === $tag[$j]) {
                    $temp = false;
                    break;
                }
            }
            if ($temp) {
                $t = new Tags();
                $t->tag_name = $product_tags_toArray[$i];
                $qltag->ql_tags_tag_id = count($tag);
                $qltag->save();
                $t->save();
            }
            else {
                $t = Tags::select('tag_id')->where('tag_name',$product_tags_toArray[$i])->get()->toArray();
                $qltag->ql_tags_tag_id = $t['tag_id'];
                $qltag->save();
            }
        }
        return [
            'status' => 0,
            'message' => 'Successfull'
        ];
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
            'product_type' => $products['product_type'],
            'product_stock_number' => $products['product_stock_number'],
            'product_name' => $products['product_name'],
            'product_unit_string' => $products['product_unit_string'],
            'product_unit_quantity' => $products['product_unit_quantity'],
            'product_description' => $products['product_description'],
            'product_retail_price' => $products['product_retail_price'],
            'product_barcodes' => $this->collectBarcode($products['barcodes']),
            'product_ql_tags' => $this->collectQLTag($products['qltags'])
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
            $bar->barcode_id = $products[$i]['barcode_id'];
            $bar->barcode_product_id = $products[$i]['barcode_product_id'];
            $bar->barcode_name = $products[$i]['barcode_name'];
            $bar->barcode_img = $products[$i]['barcode_img'];
            array_push($arr,$bar);
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
}
