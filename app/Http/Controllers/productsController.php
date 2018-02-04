<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Products;
use App\Barcodes;
use App\Tags;
use App\QLTags;
use DB;

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
        $products = Products::with(array(
            'barcodes',
            'qltags' => function($query){
                $query->with('tags');
            }
        ))
        ->orderBy('product_id','asc')
        ->paginate($limit);
        return response()->json($this->transformCollection($products),200);
    }
    public function transformCollection($products) {
        //Chuyển truy vấn dạng object thành mảng
        $productsToArray = $products->toArray();
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
        //$show = json_decode(json_encode($products));
        //Trả về định dạng cho dữ liệu
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
        $product = new Products;
        if (!$request->input('product_stock_number') || !$request->input('product_name') || !$request->input('product_retail_price')) {
            return Response::json([
                'error' => [
                    'status' => 1,
                    'message' => 'Hãy cung cấp đủ thông tin'
                ]
            ],422);
        }
        
        $product_id = Products::select('product_id')->max('product_id') + 1;
        $product->product_type = 'Regular product';
        $product->product_stock_number = $request->input('product_stock_number');
        $product->product_name = $request->input('product_name');
        $product->product_unit_string = 'PC';
        $product->product_unit_quantity = 1;
        $product->product_cost = $request->input('product_cost')?$request->input('cost'):0;
        $product->product_retail_price = $request->input('product_retail_price')?$request->input('product_retail_price'):0;
        $product->product_description = $request->input('product_description')?$request->input('product_description'):'';
        $product->product_min_quantity = $request->input('product_min_quantity')?$request->input('product_min_quantity'):0;
        $product_max_quantity = $request->input('product_max_quantity')?$request->input('product_max_quantity'):0;
        $product->product_active = 1;
        $product->save();

        if ($bc = $request->input('product_barcode')){
            $listBc = explode(',', $bc);
            for ($i = 0;$i < count($listBc);$i++) {
                $barcode = new Barcodes;
                $barcode->barcode_product_id = $product_id;
                $barcode->barcode_name = $listBc[$i];
                $barcode->save();
            }
        }

        $tag = $request->input('product_tag');
        $listTag = explode(',', $tag);
        $listTagExisted = Tags::select('tag_id','tag_name')->get()->toArray();
        for ($i = 0;$i < count($listTag);$i++) {
            $t = false;
            $tag_id = 0;
            for ($j = 0;$j < count($listTagExisted);$j++) {
                if ($listTag[$i] === $listTagExisted[$j]['tag_name']) {
                    $tag_id = $listTagExisted[$j]['tag_id'];
                    $t = true;
                    break;
                }
            }
            if (!$t) {
                $tags = new Tags;
                $tags->tag_name = $listTag[$i];
                $tags->save();
            }

            $qltag = new QLTags;
            $qltag->ql_tags_product_id = $product_id;
            if ($t) $qltag->ql_tags_tag_id = $tag_id;
            else $qltags->ql_tags_tag_id = Tags::select('tag_id')->max('tag_id') + 1;
            $qltags->save();
        }
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
        //$listProduct = $request->
        // for ($i = 0;$i < count($listProduct);$i++) {
        //     $product = Products::find($listProduct);
        //     if ($product->product_active === 1) $product->product_active = 0;
        //     else if ($product->product_active === 0) $product->product_active = 1;
        //     $product->save();
        // }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request)
    {
        //
        //$listProduct = $request->...
        // for ($i = 0;$i < count($listProduct);$i++) {
        //     $product = Products::find($listProduct[$i]);
        //     $product->delete();
        // }
    }
    public function productBarcode($product) {
        $arr = [];
        for ($i = 0;$i < count($product);$i++) {
            array_push($arr, $product['barcode_id'],$product['barcode_name'],$product['barcode_img']);
        }
        return $arr;
    }

    
}
