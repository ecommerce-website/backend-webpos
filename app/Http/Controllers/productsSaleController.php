<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Products;

class productsSaleController extends Controller
{
    //
    public function index(Request $request,$id)
    {
        //
        $sales = Products::with(array(
            'qlinvoices' => function($query) {
            	$query->with('invoices');
            }
        ))
        ->where('product_id',$id)
        ->get();
        return response()->json($this->transformCollection($sales),200);
    }
    public function transformCollection($sales) {
        //Chuyển truy vấn dạng object thành mảng
        $salesToArray = $sales->toArray();
        return [    
            'status' => 0,
            'messages' => 'Return success!',
            'data' => array_map([$this,'transformData'],$salesToArray)
        ];
    }
    public function transformData($sales) {
        return [
            'product_id' => $sales['product_id'],
            'product_stock_number' => $sales['product_stock_number'],
            'product_name' => $sales['product_name'],
            'product_on_hand' => $sales['product_on_hand'],
            'ql_invoices' => $this->collectQLInvoices($sales['qlinvoices'])
        ];
    }
    public function collectQLInvoices($sales) {
    	$arr = [];
    	for ($i = 0;$i < count($sales);$i++) {
    		$obj = new class{};
    		$obj->ql_invoices_id = $sales[$i]['ql_invoices_id'];
    		$obj->invoices = $this->collectInvoices($sales[$i]['invoices']);
    		array_push($arr,$obj);
    	}
    	return $arr;
    }
    public function collectInvoices($sales) {
    	$obj = new class{};
    	$obj->invoice_id = $sales['invoice_id'];
    	$obj->invoice_date = $sales['invoice_date'];
    	$obj->invoice_transaction_type = $sales['invoice_transaction_type'];
    	$obj->invoice_quantity_bought = $sales['invoice_quantity_bought'];
    	$obj->invoice_total = $sales['invoice_total'];
    	return $obj;
    }
}
