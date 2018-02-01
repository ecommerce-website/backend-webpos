<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Invoices;

class invoicesController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
        $invoices = Invoices::orderBy('invoice_id','desc')->paginate(10);
        return response()->json($this->transformCollection($invoices),200);
    }

    public function transformCollection($invoices) {
        $invoicesToArray = $invoices->toArray();
        return [
            'current_page' => $invoicesToArray['current_page'],
            'first_page_url' => $invoicesToArray['first_page_url'],
            'last_page_url' => $invoicesToArray['last_page_url'],
            'next_page_url' => $invoicesToArray['next_page_url'],
            'prev_page_url' => $invoicesToArray['prev_page_url'],
            'per_page' => $invoicesToArray['per_page'],
            'from' => $invoicesToArray['from'],
            'to' => $invoicesToArray['to'],
            'total' => $invoicesToArray['total'],
            'status' => 0,
            'messages' => 'Return success!',
            'data' => array_map([$this,'transform'], $invoicesToArray['data'])
        ];
    }

    public function transform($invoice) {
        return [
            'invoice_id' => $invoice['invoice_id'],
            'invoice_ref' => $invoice['invoice_ref'],
            'invoice_date' => $invoice['created_at'],
            'invoice_transaction_type' => $invoice['invoice_transaction_type'],
            'invoice_status' => $invoice['invoice_status']
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
