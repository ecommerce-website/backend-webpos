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
    /*
    GET ALL TRANSACTIONS
    */
    public function index() {
        //
        $transactions = Transactions::orderBy('transaction_id','desc')
        ->paginate(10);
        return response()->json($transactions,200);
    }
    public function search(Request $request) {
        $query = $request->input('query');
        if (is_null($query['transaction_name']) && is_null($query['transaction_date_begin']) && is_null($query['transaction_date_end'])) {
            $transactions = Transactions::orderBy('transaction_id','desc')->paginate(10);
            return response()->json($this->transformCollection($transactions),200);
        }
        else {
            if (is_null($query['transaction_date_begin'])) {
                if (is_null($query['transaction_date_end'])) {
                    $transactions = Transactions::orderBy('transaction_id','desc')
                    ->where('transaction_ref','LIKE','%'.$query['transaction_name'].'%')
                    ->paginate(10);
                    return response()->json($this->transformCollection($transactions),200);
                }
                else {
                    $transactions = Transactions::orderBy('transaction_id','desc')
                    ->where([
                        ['transaction_ref','LIKE','%'.$query['trasaction_name'].'%'],
                        ['transaction_created_at','<=',$query['transaction_date_end']]
                    ])
                    ->paginate(10);
                    return response()->json($this->transformCollection($transactions),200);
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
                    return response()->json($this->transformCollection($transactions),200);
                }
                else {
                    $transactions = Transactions::orderBy('transaction_id','desc')
                    ->where([
                        ['transaction_ref','LIKE','%'.$query['trasaction_name'].'%'],
                        ['transaction_created_at','>=',$query['transaction_date_begin']],
                        ['transaction_created_at','<=',$query['transaction_date_end']]
                    ])
                    ->paginate(10);
                    return response()->json($this->transformCollection($transactions),200);
                }
            }
        }
    }
    public function searchProduct(Request $request) {
        $query = $request->input('query');
        $product = Products::where('product_name','LIKE','%'.$query['product_name'].'%')
        ->orWhere('product_unit_string','LIKE','%'.$query['product_unit_string'].'%')
        ->orWhere('product_stock_number','LIKE','%'.$query['product_stock_number'].'%')
        ->paginate(10);
        return response()->json($product,200);
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
    STORE NEW TRANSACTIONS
    */
    public function store(Request $request){
        //require
        $transaction = $request->input('transaction');

        if (is_null($transaction['transaction_type']) || empty($transaction['transaction_product'])) {
            return response()->json('not enough information!',422);
        }

        //optional
        $transaction_type = $transaction['transaction_type'];
        $arrProduct = $transaction['transaction_product'];
        $transaction_supplier = $transaction['transaction_supplier'];
        $transaction_ref = $transaction['transaction_ref'];
        if ($transaction_ref === '') $transaction_ref = 'SUPREC-'.strval($transaction_id);
        else $transaction_ref = $transaction['transaction_ref'];
        $transaction_remark = $transaction['transaction_remark'];
        

        $transactions = new Transactions();
        $transactions->transaction_type = $transaction_type;
        $transactions->transaction_ref = $transaction_ref;
        $transactions->transaction_status = 'Posted';
        $transactions->transaction_user = 'th3Wiz';
        $transactions->save();

        $transaction_id = Transactions::select('transaction_id')->max('transaction_id');
        for ($i = 0;$i < count($arrProduct);$i++) {
            QLTransactions::updateOrCreate([
                'ql_transactions_transaction_id' => $transaction_id,
                'ql_transactions_product_id' => $arrProduct[$i]['product_id'],
                'ql_transactions_discount' => $arrProduct[$i]['product_discount'],
                'ql_transactions_quantity_bought' => $arrProduct[$i]['product_quantity_bought']
            ]);
            Products::updateOrCreate([
                'product_on_hand' => ('products.product_on_hand' + $arrProduct[$i]['product_quantity_bought'])
            ]);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    /*
    GET TRANSACTION BY ID
    */
    public function show($id) {
        //
        $transaction = Transactions::with(array(
            'qltransactions' => function($query) {
                $query->with('products');
            }
        ))
        ->where('transaction_id',$id)
        ->first();
        if (is_null($transaction)) {
            return response()->json('no id found!',422);
        }
        return response()->json($transaction,200);
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
    public function update(Request $request, $id){
        //
        $transaction = Transactions::where('transaction_id',$id)
        ->first();
        if (is_null($transaction)) {
            return response()->json('no id found!',422);
        }
        if ($transaction['transaction_status'] === 'Posted') $transaction['transaction_status'] = 'Voided';
        $transaction->save();
        return response('success',200);
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
