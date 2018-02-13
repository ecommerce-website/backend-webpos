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
    /*
    GET ALL INVOICE
    */
    public function index() {
        //
        $invoices = Invoices::orderBy('invoice_id','desc')->paginate(10);
        return response()->json($invoices,200);
    }
    /*
    FILTER INVOICE
    */
    public function filter(Request $request) {
        //
        $invoice = $request->input('invoice');
        if (is_null($invoice['invoice_ref']) && is_null($invoice['invoice_date_begin']) && is_null($invoice['invoice_date_end'])) {
            $invoices = Invoices::orderBy('invoice_id','desc')->paginate(10);
            return response()->json($invoices,200);
        }
        else {
            if (is_null($invoice['invoice_date_begin'])) {
                if (is_null($invoice['invoice_date_end'])) {
                    $invoices = Invoices::orderBy('invoice_id','desc')->where([
                                    ['invoice_ref','LIKE','%'.$invoice['invoice_ref'].'%']
                                ])
                                ->paginate(10);
                    return response()->json($invoices,200);
                }
                else {
                    $invoices = Invoices::orderBy('invoice_id','desc')->where([
                                    ['invoice_ref','LIKE','%'.$invoice['invoice_ref'].'%']
                                ])
                                ->whereDate('created_at','<=','%'.$invoice['invoice_date_end'].'%')
                                ->paginate(10);
                    return response()->json($invoices,200);
                }
            }
            else {
                if (is_null($invoice['invoice_date_end'])) {
                    $invoices = Invoices::orderBy('invoice_id','desc')->where([
                                    ['invoice_ref','LIKE','%'.$invoice['invoice_ref'].'%']
                                ])
                                ->whereDate('created_at','>=','%'.$invoice['invoice_date_begin'].'%')
                                ->paginate(10);
                    return response()->json($invoices,200);
                }
                else {
                    $invoices = Invoices::orderBy('invoice_id','desc')->where([
                                    ['invoice_ref','LIKE','%'.$invoice['invoice_ref'].'%']
                                ])
                                ->whereDate([
                                    ['created_at','<=','%'.$invoice['invoice_date_end'].'%'],
                                    ['created_at','>=','%'.$invoice['invoice_date_begin'].'%']
                                ])
                                ->paginate(10);
                    return response()->json($this->transformCollection($invoices),200);
                }
            }
        }
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
    /*
    SHOW INVOICE WITH ID
    */
    public function show($id)
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
        if (is_null($invoice)) return response()->json('no id found!',422);
        return response()->json($invoice,200);
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
    /*
    UPDATE INVOICE
    */
    public function update(Request $request, $id)
    {
        //
        $invoice = Invoices::where('invoice_id',$id)->first();
        if (is_null($invoice)) return response()->json('no id found!',422);
        if ($invoice->invoice_status === 'Posted') $invoice->invoice_status = 'Voided';
        $invoice->save();
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
