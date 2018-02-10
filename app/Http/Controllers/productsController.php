<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Products;
use App\Barcodes;
use App\Tags;
use App\QLTags;
use DB;
use \Milon\Barcode\DNS1D;

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
        $products = $request->input('product');
        if (is_null($products["product_stock_number"]) || is_null($products['product_name']) || is_null($products['product_retail_price'])) {
            return response()->json([
                'error' => [
                    'status' => 1,
                    'message' => 'Hãy cung cấp đủ thông tin'
                ]
            ],422);
        }

        $query = Products::select('product_stock_number')->where('product_stock_number',$products['product_stock_number'])->first();
        if (!is_null($query)) {
            return response()->json([
                'error' => [
                    'status' => 4,
                    'message' => 'sản phẩm đã tồn tại'
                ]
            ]);
        }
       
        $product->product_stock_number = $products["product_stock_number"];
        $product->product_name = $products["product_name"];
        $product->product_retail_price = $products["product_retail_price"];
        
        $product->product_type = 'Regular product';
        $product->product_unit_string = 'PC';
        $product->product_unit_quantity = 1;
        if (!is_null($products["product_cost"])) $product->product_cost = intval($products["product_cost"]);
        else $product->product_cost = 0;

        if (!is_null($products["product_min_quantity"])) $product->product_min_quantity = intval($products["product_min_quantity"]);
        else $product->product_min_quantity = 0;
        if (!is_null($products["product_max_quantity"])) $product->product_max_quantity = intval($products["product_max_quantity"]);
        else $product->product_max_quantity = 0;
        $product->product_description = $products["product_description"];
        $product->product_active = 1;
        $product->save();

        $barcode = new Barcodes;
        $product_id = Products::select('product_id')->max('product_id');
        $barcode->barcode_product_id = $product_id;
        $barcode->barcode_name = "QT".str_pad(strval($product_id),10,"0",STR_PAD_LEFT);

        $barcode->barcode_img = DNS1D::getBarcodePNG($barcode->barcode_name,"C93",1,50);
        $barcode->save();

        $product_tags = $products["product_tags"];
        $listTag = explode(',', $product_tags);
        foreach ($listTag as $value) {
            Tags::updateOrCreate(['tag_name' => $value]);
            $q = Tags::where('tag_name',$value)->first();
            QLTags::updateOrCreate(['ql_tags_product_id' => $product_id,'ql_tags_tag_id' => $q->tag_id]);
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
    public function update(Request $request)
    {
        //
        $products = $request->input("product");
        if (empty($products)) {
            return response()->json([
                'error' => [
                    'status' => 2,
                    'message' => 'No ID found'
                ]
            ]);
        }
        foreach ($products as $p) {
            $product = Products::find($p);
            if ($product->product_active === 1) $product->product_active = 0;
            else if ($product->product_active === 0) $product->product_active = 1;
            $product->save();
        }
        return response()->json([
            'status' => 0,
            'message' => 'success'
        ]);
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
        $product = $request->input('product');
        if (empty($product)) {
            return response()->json([
                'error' => [
                    'status' => 2,
                    'message' => 'No ID found'
                ]
            ],422);
        }

        foreach ($product as $p) {
            $product = Products::find($p);
            $product->delete();
        }
        return response()->json([
            'status' => 0,
            'message' => 'success'
        ]);
    }
    public function productBarcode($product) {
        $arr = [];
        for ($i = 0;$i < count($product);$i++) {
            array_push($arr, $product['barcode_id'],$product['barcode_name'],$product['barcode_img']);
        }
        return $arr;
    }

    
}
