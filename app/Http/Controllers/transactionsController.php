<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Transactions;

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
            'data' => array_map([$this,'transform'],$transactionsToArray['data'])
        ];
    }
    public function transform($transactions) {
        return [
            'transaction_id' => $transactions['transaction_id'],
            'transaction_ref' => $transactions['transaction_ref'],
            'transaction_date' => $transactions['transaction_date'],
            'transaction_type' => $transactions['transaction_type'],
            'transaction_date' => $transactions['transaction_date'],
            'transaction_status' => $transactions['transaction_status'],
            'transaction_user' => $transactions['transaction_user']
        ];
    }
}
