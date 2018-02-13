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
    /*
    GET ALL PRODUCTS
    */
    public function index(Request $request) {
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
        return response()->json($products,200);
    }

    /*
    FILTER PRODUCTS
    */
    public function filter(Request $request) {
        $query = $request->input('product');
        if (is_null($query['product_active']) && is_null($query['product_name']) && empty($query['product_tags'])) {
            $products = Products::with(
                array(
                    'barcodes',
                    'qltags' => function($query) {
                        $query->with('tags');
                    }
                )
            )
            ->orderBy('product_id','desc')
            ->paginate(10);
            return response()->json($products,200);
        }
        else {
            if (!is_null($query['product_name'])) $productName = $query['product_name'];
            else $productName = "";
            if (!is_null($query['product_active'])) $productActive = strval($query['product_active']);
            else $productActive = "";
            $productTags = $query['product_tags'];
            if (empty($productTags)){
                $products = Products::with(
                    array(
                        'barcodes',
                        'qltags' => function($query) {
                            $query->with('tags');
                        }
                    )
                )
                ->where([
                    ['product_active','LIKE','%'.$productActive.'%'],
                    ['product_name','LIKE','%'.$productName.'%']
                ])
                ->orderBy('product_id','desc')
                ->paginate(10);
                return response()->json($products,200);
            }
            else {
                $products = Products::with(
                    array(
                        'barcodes',
                        'qltags' => function($query) {
                            $query->with('tags');
                        }
                    )
                )
                ->whereHas('qltags',function($que) use ($productTags){
                    $que->whereHas('tags',function($que) use ($productTags){
                        $que->whereIn('tag_name',$productTags);
                    });
                })
                ->where([
                    ['product_active','LIKE','%'.$productActive.'%'],
                    ['product_name','LIKE','%'.$productName.'%'],
                ])
                ->orderBy('product_id','desc')
                ->paginate(10);
                return response()->json($products,200);
            }
        }
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
    /*
    STORE PRODUCT
    */
    public function store(Request $request) {
        //

        $product = new Products;
        $products = $request->input('product');
        if (is_null($products["product_stock_number"]) || is_null($products['product_name']) || is_null($products['product_retail_price'])) {
            return response()->json('not enough information',422);
        }

        $query = Products::select('product_stock_number')->where('product_stock_number',$products['product_stock_number'])->first();

        if (!is_null($query)) {
            return response()->json('product is already in inventory',422);
        }
        //Save product
        $product->product_stock_number = $products["product_stock_number"];
        $product->product_name = $products["product_name"];
        $product->product_retail_price = $products["product_retail_price"];
        $product->product_type = 'Regular Product';
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
        //Save barcode
        $barcode = new Barcodes;
        $product_id = Products::select('product_id')->max('product_id');
        $barcode->barcode_product_id = $product_id;
        $barcode->barcode_name = "QT".str_pad(strval($product_id),10,"0",STR_PAD_LEFT);
        $barcode->barcode_img = DNS1D::getBarcodePNG($barcode->barcode_name,"C93",1,50);
        $barcode->save();
        //Save tags
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
    /*
    SHOW PRODUCT WITH ID
    */
    public function show($id) {
        //
         $products = Products::with(array(
            'barcodes',
            'qltags' => function($query){
                $query->with('tags');
            }
        ))
        ->where('product_id',$id)
        ->orderBy('product_id','asc')
        ->first();
        if (is_null($products)) {
            return response()->json('no id found!',422);
        }
        return response()->json($products,200);
    }

    /*
    SHOW SALE'S HISTORY PRODUCT WITH ID
    */
    public function sale($id) {
        $products = Products::with(
            array(
                'qlinvoices' => function($query) {
                    $query->with('invoices');
                }
            )
        )
        ->where('product_id',$id)
        ->first();
        if (is_null($products)) {
            return response()->json('no id found!',422);
        }
        return response()->json($products,200);
    }


    /*
    SHOW TRANSACTION'S HISTORY PRODUCT WITH ID
    */
    public function transaction($id) {
        $products = Products::with(
            array(
                'qltransactions' => function($query){
                    $query->with('transactions');
                }
            )
        )
        ->where('product_id',$id)
        ->first();
        if (is_null($products)) {
            return response()->json('no id found!',422);
        }
        return response()->json($products,200);
    }
    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit(Request $request,$id) {
        //
        //Lấy toàn bộ request
        $query = $request->input('product');
        //Nếu stock number, price và name trống thì trả về thông báo
        if (is_null($query['product_stock_number']) || is_null($query['product_name']) || is_null($query['product_retail_price'])) {
            return response()->json('not enough information!',422);
        }
        $product = Products::where('product_stock_number',$query['product_stock_number'])->first();
        if (is_null($product)) {
            return response()->json('product is already in inventory!',422);
        }
        /*Edit product*/
        $product->product_stock_number = $query['product_stock_number'];
        $product->product_name         = $query['product_name'];
        $product->product_retail_price = $query['product_retail_price'];
        $product->product_cost         = $query['product_cost'];
        $product->product_description  = $query['product_description'];
        $product->product_min_quantity = $query['product_min_quantity'];
        $product->product_max_quantity = $query['product_max_quantity'];
        $product->save();

        /*Edit tags*/
        $product_tags         = $query['product_tags'];
        $product_tags_toArray = explode(",", $product_tags);
        foreach ($product_tags_toArray as $value) {
            Tags::updateOrCreate(['tag_name' => $value]);
            $q = Tags::where('tag_name',$value)->first();
            QLTags::updateOrCreate(['ql_tags_product_id' => $id,'ql_tags_tag_id' => $q->tag_id]);
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request) {
        //
        $products = $request->input("product");
        if (empty($products)) {
            return response()->json('no id found!',422);
        }
        foreach ($products as $p) {
            $product = Products::find($p);
            if ($product->product_active === 1) $product->product_active = 0;
            else if ($product->product_active === 0) $product->product_active = 1;
            $product->save();
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request) {
        //
        $products = $request->input('product');
        if (empty($products)) {
            return response()->json('no id found!',422);
        }

        foreach ($products as $p) {
            $product = Products::find($p);
            $product->delete();
        }
    }
    /*
    SEARCH PRODUCTS BY BARCODE OR NAME
    */
    public function query(Request $request){
        //
        $search = $request->input('query');
        if (is_null($search)) {
            $product = Products::paginate(10);
            return response()->json($product,200);
        }
        else {
            $product = Products::join('barcodes','products.product_id','=','barcodes.barcode_product_id')
            ->where('product_name','LIKE','%'.$search.'%')
            ->orWhere('barcode_name', 'LIKE', '%'.$search.'%')
            ->paginate(10);
            return response()->json($product, 200);
        }
       
    }
    /*
    SEARCH PRODUCT BY BARCODE
    */
    public function search(Request $request){
        //
        $barcode_name = $request->input('barcode_name');
        $products = Products::join('barcodes','products.product_id','=','barcodes.barcode_product_id')
        ->where('barcode_name','=',$barcode_name)
        ->distinct();
        ->first();
        return response()->json($products, 200);
    }
}
