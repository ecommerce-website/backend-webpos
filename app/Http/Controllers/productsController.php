<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Products;
use App\Tags;
use App\QLTags;
use DB;
use \Milon\Barcode\DNS1D;
use Image;
use File;

class productsController extends Controller
{

    public function index(Request $request){
        $limit = $request->input('limit')?$request->input('limit'):9;
        $products = Products::with(array(
            'qltags' => function($query){
                $query->with('tags');
            }
        ))
        ->orderBy('product_id','desc')
        ->paginate($limit);
        foreach($products as $product){
            $product->product_img = $request->root().'/'.$product->product_img;
        }
        return response()->json($products,200);
    }

    public function create(){
        
    }
    
    public function store(Request $request){
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
        $product->product_img = 'storage/img/no-image.png';

        if (!$product->save()){
            return response()->json([
                'error' => [
                    'status' => 1,
                    'message' => 'Lưu product gặp lỗi!'
                ]
            ],422);
        }

        $product->product_barcode_name = "QT".str_pad(strval($product->product_id),6,"0",STR_PAD_LEFT);
        $product->product_barcode_img = DNS1D::getBarcodePNG($product->product_barcode_name,"C128", 3, 150);
        if (!$product->save()){
            $product->delete();
            return response()->json([
                'error' => [
                    'status' => 1,
                    'message' => 'Lưu barcode gặp lỗi!'
                ]
            ],422);
        }

        if (!is_null($products["product_img"])){
            $img_data = $products["product_img"];
            $path = 'storage/img/'."QT".str_pad(strval($product->product_id),6,"0",STR_PAD_LEFT).".png";
            $img = Image::make(file_get_contents($img_data));
            $width = $img->width();
            $height = $img->height();
            if($width < $height){
                $img->crop($width, $width, 0, (int)(($height-$width)/2));
                $img->resize(min($width, 250), min($width, 250));
            } else {
                $img->crop($height, $height, (int)(($width-$height)/2), 0);
                $img->resize(min($height, 250), min($height, 250));
            }
            $img->save($path);
            $product->product_img = $path;
            $product->save();
        }

        // $product_tags = $products["product_tags"];
        // $listTag = explode(',', $product_tags);
        // foreach ($listTag as $value) {
        //     Tags::updateOrCreate(['tag_name' => $value]);
        //     $q = Tags::where('tag_name',$value)->first();
        //     QLTags::updateOrCreate(['ql_tags_product_id' => $product_id,'ql_tags_tag_id' => $q->tag_id]);
        // }
        return response()->json(array('success' => true), 200);
        
        
    }

    public function show($id){
        $product = Products::with(array(
            'qltags' => function($query) {
                $query->with('tags');
            },
            'qlinvoices' => function($query) {
                $query->with('invoices')->orderBy('ql_invoices_id', 'desc')->take(100);
            },
            'qltransactions' => function($query) {
                $query->with('transactions')->orderBy('ql_transactions_id', 'desc')->take(100);
            },
        ))
        ->where('product_id',$id)
        ->first();
        
        return response()->json($product,200);
    }

    public function edit(Request $request, $id){
        //Lẩy toàn bộ request
        $obj = $request->input('product');

        //Nếu stock number, price và name trống thì trả về thông báo
        if (is_null($obj['product_stock_number']) || is_null($obj['product_name']) || is_null($obj['product_retail_price'])) {
            return response()->json([
                'error' => [
                    'status' => 1,
                    'message' => 'Hãy cung cấp đủ thông tin'
                ]
            ],422);
        }
        /*lấy sản phẩm từ request*/
        $product_stock_number = $obj['product_stock_number'];
        $product_name = $obj['product_name'];
        $product_retail_price = $obj['product_retail_price'];
        $product_cost = $obj['product_cost'];
        $product_description = $obj['product_description'];
        $product_min_quantity = $obj['product_min_quantity'];
        $product_max_quantity = $obj['product_max_quantity'];
        $product_tags = $obj['product_tags'];


        /*Tách chuỗi tag thành mảng*/
        // $product_tags_toArray = explode(",", $product_tags);

        /*Thêm tag nếu có tag mới*/
        // foreach ($product_tags_toArray as $value) {
        //     Tags::updateOrCreate(['tag_name' => $value]);
        //     $q = Tags::where('tag_name',$value)->first();
        //     QLTags::updateOrCreate(['ql_tags_product_id' => $id,'ql_tags_tag_id' => $q->tag_id]);
        // }

        /*Tìm sản phẩm cần sửa*/
        $product = Products::with(array(
                    'qltags' => function($query){
                        $query->with('tags');
                    }
                ))->where('product_id',$id)
                ->first();
        /*Cập nhật các thuộc tính của sản phẩm*/
        $product->product_stock_number = $product_stock_number;
        $product->product_name = $product_name;
        $product->product_retail_price = $product_retail_price;
        $product->product_cost = $product_cost;
        $product->product_description = $product_description;
        $product->product_min_quantity = $product_min_quantity;
        $product->product_max_quantity = $product_max_quantity;
        $product->save();

        if (!is_null($obj["product_img"])){
            $img_data = $obj["product_img"];
            $path = 'storage/img/'."QT".str_pad(strval($product->product_id),6,"0",STR_PAD_LEFT).".png";
            $img = Image::make(file_get_contents($img_data));
            $width = $img->width();
            $height = $img->height();
            if($width < $height){
                $img->crop($width, $width, 0, (int)(($height-$width)/2));
                $img->resize(min($width, 250), min($width, 250));
            } else {
                $img->crop($height, $height, (int)(($width-$height)/2), 0);
                $img->resize(min($height, 250), min($height, 250));
            }
            $img->save($path);
            $product->product_img = $path;
            $product->save();
        }

        $product->product_img = $request->root().'/'.$product->product_img;

        /*Thêm dữ liệu vào bảng trung gian*/
        return response()->json(array('status' => 0, 'message' => 'success', 'editedProduct' => $product),200);
    }

    public function update(Request $request){
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
        return response()->json(array('status' => 0, 'message' => 'success'),200);
    }

    public function destroy(Request $request){
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
            $product->qltags()->delete();
            $product->qlinvoices()->delete();
            $product->qltransactions()->delete();
            if($product->product_img != 'storage/img/no-image.png' && file_exists($product->product_img)) File::delete($product->product_img);
            $product->delete();
        }
        return response()->json(array('status' => 0, 'message' => 'success'),200);
    }

    public function productBarcode($product) {
        $arr = [];
        for ($i = 0;$i < count($product);$i++) {
            array_push($arr, $product['product_barcode_name'],$product['product_barcode_img']);
        }
        return $arr;
    }
    
    public function filter(Request $request){
        //
        $obj = $request->input('product');
        if (is_null($obj['product_name']) && is_null($obj['product_active']) && empty($obj['product_tag'])) {
            $products = Products::with(array(
                'qltags' => function($query){
                    $query->with('tags');
                }
            ))->orderBy('product_id')->paginate(10);
            return response()->json($products,200);
        }
        else {
            if (!is_null($obj['product_name'])) $productName = $obj['product_name'];
            else $productName = "";
            if (!is_null($obj['product_active'])) $productActive = strval($obj['product_active']);
            else $productActive = "";
            $productTag = $obj['product_tag'];
            $products = Products::whereHas('qltags', function($query) use($productTag){
                    $query->whereHas('tags', function($query) use($productTag) {
                        $query->whereIn('tag_name',$productTag);
                    });
                }
            )
            ->with(array(
                'qltags' => function($query) {
                    $query->with('tags');
                }
            ))
            ->orderBy('product_id')
            ->where([
                ['product_name','LIKE','%'.$productName.'%'],
                ['product_active','LIKE','%'.$productActive.'%']
            ])
            ->paginate(10);
            return response()->json($products,200);
        }
    }

    public function query(Request $request){
        $search = $request->input('query');
        if ($search === "") {
            $product = Products::with(
                array(
                    'qltags'=>function($query) {
                        $query->with('tags');
                    }
                )
            )
            ->orderBy('product_id','desc')
            ->paginate(9);               
            foreach($products as $product){
                $product->product_img = $request->root().'/'.$product->product_img;
            }
            return response()->json($product,200);
        }
        else {
            $products = Products::with(
                array(
                    'qltags'=>function($query) {
                        $query->with('tags');
                    }
                )
            )
            ->where('product_name', 'LIKE', '%'.$search.'%')
            ->orWhere('product_barcode_name', 'LIKE', '%'.$search.'%')
            ->distinct()
            ->orderBy('product_id','desc')
            ->paginate(9);                
            foreach($products as $product){
                $product->product_img = $request->root().'/'.$product->product_img;
            }
            return response()->json($products, 200);
        }
       
    }
    public function search(Request $request){
        $barcode_name = $request->input('barcode_name');
        $product = Products::with(
                array(
                    'qltags'=>function($query) {
                        $query->with('tags');
                    }
                )
            )
            ->where('product_barcode_name', '=', $barcode_name)
            ->distinct()
            ->orderBy('product_id','desc')
            ->first();
        if(is_null($product)){
            return response()->json([
                'error' => [
                    'status' => 2,
                    'message' => 'No barcode found'
                ]
            ],422);
        }
        $product->product_img = $request->root().'/'.$product->product_img;
        return response()->json($product, 200);
    }
    
}
