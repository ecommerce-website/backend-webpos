<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Invoices;

class invoicesFilter extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index($name = null,$status = null,$dateBegin = null,$dateEnd = null) 
    {
        //
        
        // if ($name === null && $type === null && $status === null && $dateBegin === null && $dateEnd === null) {
        //     $invoice = Invoices::orderBy('invoice_id','desc')->paginate(10);
        // }
        // else {
        //     $invoice = Invoices::orderBy('invoice_id','desc')->where([
        //         ['invoice_ref','LIKE','%$name%'],
        //         ['invoice_status','LIKE','%$status%']
        //     ])
        //     ->paginate(10);
        // }

        // echo "<pre>";
        // print_r($invoice->toArray());
        // echo "</pre>";
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
