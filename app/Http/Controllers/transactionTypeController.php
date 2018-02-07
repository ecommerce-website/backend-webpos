<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Transactions;

class transactionTypeController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
        $transactionParRef = Transactions::orderBy('transaction_id','asc')
        ->where('transaction_type','Receive From Supplier')
        ->get();
        return response()->json($this->transformCollection($transactionParRef),200);
    }

    public function transformCollection($transactionParRef) {
        $transToArray = $transactionParRef->toArray();
        return [
            'status' => 0,
            'message' => 'Successfull!',
            'data' => array_map([$this,'transform'],$transToArray)
        ];
    }

    public function transform($transactionParRef) {
        return [
            'transaction_id' => $transactionParRef['transaction_id'],
            'transaction_type' => $transactionParRef['transaction_type'],
            'transaction_ref' => $transactionParRef['transaction_ref']
        ];
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
}
