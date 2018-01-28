<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Transactions;


class transactionController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index($id)
    {
        //
        $transactions = Transactions::with(array(
            'qltransactions' => function($query) {
                $query->with('products');
            }
         ))
         ->select(
            'transaction_id',
            'transaction_ref',
            'transaction_date',
            'transaction_status',
            'transaction_user'
        )
        ->where('transaction_id',$id)
        ->get();
        return response()->json($this->transformCollection($transactions),200);
        
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
        $transaction_id = Transactions::select('transaction_id')->max('transaction_id') + 1;
        $transaction_type = $request->input('transaction_type');
        $transaction_supplier = $request->input('transaction_supplier')?$request->input('transaction_type'):'';
        $transaction_ref = $request->input('transaction_ref')?$request->input('transaction_ref'):'';
        $transaction_remark = $request->input('transaction_remark')?$request->input('transaction_remark'):'SUPREC-'.strval($transaction_id);
        //Lay ve mang product
        //$arrProduct = $request-> ;
        
        $transaction = new Transactions();
        $transaction->transaction_id = $transaction_id;
        $transaction->transaction_type = $transaction_type;
        $transaction->transaction_ref = $transaction_ref;
        $transaction->status = 'Posted';
        $transaction->save();

        $qlTransaction = new QLTransactions();
        $qltransaction->ql_transactions_transaction_id = $transaction_id;
        for ($i = 0;$i < count($arrProduct);$i++) {
            $qlTransaction->ql_transactions_product_id = $arrProduct['product_id'];
            $qlTransaction->ql_transactions_quantity_bought = $arrProduct['product_quantity_bought'];
            $qlTransaction->save();
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
        $transaction = Transactions::select(
            'transaction_id',
            'transaction_status'
        )
        ->where('transaction_id',$id)
        ->first();

        if ($transaction['transaction_status'] === 'Posted') $transaction['transaction_status'] = 'Voided';
        $transaction->save();
        return [
            'status' => 0,
            'message' => 'Successful!'
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
    public function transformCollection($transaction) {
        $transactionToArray = $transaction->toArray();
        return [
            'status' => 0,
            'message' => 'Successful!',
            'data' => array_map([$this,'transform'],$transactionToArray)
        ];
    }
    public function transform($transaction) {
        return [
            'transaction_id' => $transaction['transaction_id'],
            'transaction_ref' => $transaction['transaction_ref'],
            'transaction_date' => $transaction['transaction_date'],
            'transaction_user' => $transaction['transaction_user'],
            'qltransactions' => $this->collectQLTransaction($transaction['qltransactions'])
        ];
    }
    public function collectQLTransaction($transaction) {
        $arr = [];
        for ($i = 0;$i < count($transaction);$i++) {
            $obj = new class{};
            $obj->ql_transactions_id = $transaction[$i]['ql_transactions_id'];
            $obj->ql_transactions_quantity_bought = $transaction[$i]['ql_transactions_quantity_bought'];
            $obj->products = $this->collectProducts($transaction[$i]['products']);
            array_push($arr,$obj);
        }
        return $arr;
    }
    public function collectProducts($transaction) {
        $obj = new class {};
        $obj->product_id = $transaction['product_id'];
        $obj->product_name = $transaction['product_name'];
        return $obj;
    }
}
