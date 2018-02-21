<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Invoices;

class invoiceController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index($id)
    {
        //
        $invoice = Invoices::with(
            array(
                'customers',
                'ql_invoices'=>function($query) {
                    $query->with('products');
                }
            )
        )
        ->where('invoice_id',$id)
        ->first();
        if (is_null($invoice)) return response()->json([
            'error' => [
                'status' => 1,
                'message' => 'no Id found'
            ]
        ],422);
        return response()->json($invoice,200);
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
        $invoice = Invoices::where('invoice_id',$id)->first();
        if (is_null($invoice)) return response()->json([
            'error' => 1,
            'message' => 'no Id found'
        ]);
        if ($invoice->invoice_status === 'Posted') $invoice->invoice_status = 'Voided';
        $invoice->save();
        return [
            'status' => 0,
            'message' => 'success'
        ];
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
