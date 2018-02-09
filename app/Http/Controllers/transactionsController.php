<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Transactions;
use App\Products;
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
        return response()->json($this->transformCollection($transactions),200);
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
        $product = Products::
        where([
            ['product_name','LIKE','%'.$query['product_name'].'%'],
            ['product_unit_string','LIKE','%'.$query['product_unit_string'].'%'],
            ['product_stock_number','LIKE','%'.$query['product_stock_number'].'%']
        ])
        ->paginate(10);
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
    public function transformCollection($transactions) {
        $transactionsToArray = $transactions->toArray();
        return [
            'first_page_url' => $transactionsToArray['first_page_url'],
            'last_page_url' => $transactionsToArray['last_page_url'],
            'next_page_url' => $transactionsToArray['next_page_url'],
            'prev_page_url' => $transactionsToArray['prev_page_url'],
            'from' => $transactionsToArray['from'],
            'to' => $transactionsToArray['to'],
            'total' => $transactionsToArray['total'],
            'status' => 0,
            'message' => 'Successful!',
            'data' => array_map([$this,'transformProduct'],$transactionsToArray['data'])
        ];
    }
    public function transformProduct($transactions) {
        $transactions = json_decode(json_encode($transactions),true);
        return [
            'product_id' => $transactions['product_id'],
            'product_name' => $transactions['product_name'],
            'product_unit_string' => $transactions['product_unit_string'],
            'product_stock_number' => $transactions['product_stock_number']
        ];
    }
    public function transform($transactions) {
        $transactions = json_decode(json_encode($transactions),true);
        return [
            'transaction_id' => $transactions['transaction_id'],
            'transaction_ref' => $transactions['transaction_ref'],
            'transaction_type' => $transactions['transaction_type'],
            'transaction_date' => $transactions['created_at'],
            'transaction_status' => $transactions['transaction_status'],
            'transaction_user' => $transactions['transaction_user']
        ];
    }
}
