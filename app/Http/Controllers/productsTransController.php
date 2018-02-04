<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Products;


class productsTransController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index($id)
    {
        //
        $trans = Products::with(
            array(
                'qltransactions' => function($query){
                    $query->with('transactions');
                }
            )
        )
        ->where('product_id',$id)
        ->get();
        return response()->json($this->transformCollection($trans),200);
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
    public function transformCollection($trans) {
        //Chuyển truy vấn dạng object thành mảng
        $transToArray = $trans->toArray();
        return [    
            'status' => 0,
            'messages' => 'Return success!',
            'data' => array_map([$this,'transformData'],$transToArray)
        ];
    }
    public function transformData($trans) {
        return [
            'product_id' => $trans['product_id'],
            'product_stock_number' => $trans['product_stock_number'],
            'product_name' => $trans['product_name'],
            'product_on_hand' => $trans['product_on_hand'],
            'ql_transactions' => $this->collectQLInvoices($trans['qltransactions'])
        ];
    }
    public function collectQLInvoices($trans) {
        $arr = [];
        for ($i = 0;$i < count($trans);$i++) {
            $obj = new class{};
            $obj->ql_transactions_id = $trans[$i]['ql_transactions_id'];
            $obj->ql_transactions_quantity = $trans[$i]['ql_transactions_quantity_bought'];
            $obj->transactions = $this->collectInvoices($trans[$i]['transactions']);
            array_push($arr,$obj);
        }
        return $arr;
    }
    public function collectInvoices($trans) {
        $obj = new class{};
        $obj->transaction_id = $trans['transaction_id'];
        $obj->transaction_date = $trans['transaction_date'];
        $obj->transaction_type = $trans['transaction_type'];
        $obj->transaction_related_party = $trans['transaction_related_party'];
        return $obj;
    }
}
