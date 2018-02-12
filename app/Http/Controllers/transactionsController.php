<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Transactions;
use App\Products;
use App\QLTransactions;
use DB;

class transactionsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
        $transactions = Transactions::orderBy('transaction_id','desc')
        ->paginate(10);
        return response()->json($transactions,200);
    }
    public function search(Request $request) {
        $query = $request->input('query');
        if (is_null($query['transaction_name']) && is_null($query['transaction_date_begin']) && is_null($query['transaction_date_end'])) {
            $transactions = Transactions::orderBy('transaction_id','desc')->paginate(10);
            return response()->json($transactions,200);
        }
        else {
            if (is_null($query['transaction_date_begin'])) {
                if (is_null($query['transaction_date_end'])) {
                    $transactions = Transactions::orderBy('transaction_id','desc')
                    ->where('transaction_ref','LIKE','%'.$query['transaction_name'].'%')
                    ->paginate(10);
                    return response()->json($transactions,200);
                }
                else {
                    $transactions = Transactions::orderBy('transaction_id','desc')
                    ->where([
                        ['transaction_ref','LIKE','%'.$query['trasaction_name'].'%'],
                        ['transaction_created_at','<=',$query['transaction_date_end']]
                    ])
                    ->paginate(10);
                    return response()->json($transactions,200);
                }
            }
            else {
                if (is_null($query['transaction_date_end'])) {
                    $transactions = Transactions::orderBy('transaction_id','desc')
                    ->where([
                        ['transaction_ref','LIKE','%'.$query['trasaction_name'].'%'],
                        ['transaction_created_at','>=',$query['transaction_date_begin']]
                    ])
                    ->paginate(10);
                    return response()->json($transactions,200);
                }
                else {
                    $transactions = Transactions::orderBy('transaction_id','desc')
                    ->where([
                        ['transaction_ref','LIKE','%'.$query['trasaction_name'].'%'],
                        ['transaction_created_at','>=',$query['transaction_date_begin']],
                        ['transaction_created_at','<=',$query['transaction_date_end']]
                    ])
                    ->paginate(10);
                    return response()->json($transactions,200);
                }
            }
        }
    }
    public function searchProduct(Request $request) {
        $query = $request->input('query');
        $product = Products::
        where([
            ['product_name','LIKE','%'.$query['product_name'].'%'],
            ['product_unit_string','LIKE','%'.$query['product_unit_string'].'%'],
            ['product_stock_number','LIKE','%'.$query['product_stock_number'].'%']
        ])
        ->paginate(10);
        return response()->json($product,200);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(){
        
    }

    public function store(Request $request){
        //require
        $transaction = $request->input('transaction');

        if (is_null($transaction['transaction_type']) || empty($transaction['transaction_product'])) {
            return response()->json(array('status'=>1,'message'=>'Cung cap du thong tin!'),422);
        }

        //optional
        $transaction_type = $transaction['transaction_type'];
        $arrProduct = $transaction['transaction_product'];
        $transaction_supplier = $transaction['transaction_supplier'];
        $transaction_ref = $transaction['transaction_ref'];
        $transaction_remark = $transaction['transaction_remark'];
        
        $transactions = new Transactions();
        $transactions->transaction_supplier = $transaction_supplier;
        $transactions->transaction_type = $transaction_type;
        $transactions->transaction_ref = $transaction_ref;
        $transactions->transaction_remark = $transaction_remark;
        $transactions->transaction_status = 'OK';
        $transactions->transaction_user = 'admin';
        $transactions->save();

        foreach ($arrProduct as $product) {
            $qlTransaction = new QLTransactions();
            $qlTransaction->ql_transactions_transaction_id = $transactions->transaction_id;
            $qlTransaction->ql_transactions_product_id = $product['product_id'];
            $qlTransaction->ql_transactions_cost = $product['product_cost'];
            $qlTransaction->ql_transactions_quantity_bought = $product['product_quantity_bought'];
            $query = Products::where('product_id',$product['product_id'])->first();
            if (!is_null($query)){
                $query->product_on_hand = $query->product_on_hand + $qlTransaction->ql_transactions_quantity_bought;
                $query->save();
            $qlTransaction->save();
            }
        }
        return response()->json(array('success' => true), 200);
    }

    public function show($id){
        //
        $transactions = Transactions::with(array(
            'qltransactions' => function($query) {
                $query->with('products');
            }
        ))
        ->where('transaction_id',$id)
        ->first();
        if (is_null($transactions)) {
            return response()->json([
                'error' => [
                    'status'=> 2,
                    'message' => 'no Id found'
                ]
            ]);
        }
        return response()->json($transactions,200);
        
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

    public function update(Request $request, $id){
        $transaction = Transactions::where('transaction_id',$id)
        ->first();

        if (is_null($transaction)) {
            return response()->json([
                'error' => [
                    'status' => 2,
                    'message' => 'no Id found'
                ]
            ]);
        }

        if ($transaction['transaction_status'] === 'Posted') $transaction['transaction_status'] = 'Voided';
        $transaction->save();
        return response()->json([
            'status' => 0,
            'message' => 'Successful!'
        ]);
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
