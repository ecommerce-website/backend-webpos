<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Invoices;
use Carbon\Carbon;

class AddInvoiceController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
       
    }
    public function postInvoice(Request $request){

        $qlinvoice = $request->input('qlinvoice');
        $invoice_user_id = $qlinvoice->invoice_user_id;
        $invoice_customer_id = $qlinvoice->invoice_customer_id;
        $invoice_total = $qlinvoice->invoice_total;
        $invoice_quantity_bought = $qlinvoice->invoice_quantity_bought;
        $invoice_remark = $qlinvoice->invoice_remark;
        $invoice_date = $qlinvoice->invoice_date;

        $id_invoice = DB::table('invoices')->max('invoice_id')+1; 
        $ql_invoices_id = DB::table('ql_invoices')->max('ql_invoices_id')+1;
        $ql_invoices_discount = $qlinvoice->ql_invoices_discount;
        $ql_invoice_product_id = $qlinvoice->ql_invoice_product_id;
        $ql_invoice_quantity_bought = $qlinvoice->ql_invoice_quantity_bought;

        $arr1 = [
        'invoice_id' => $id,
        'invoice_user_id'=> $invoice_user_id,
        'invoice_customer_id' =>  $invoice_customer_id,
        'invoice_total' => $invoice_total,
        'invoice_quantity_bought' => $invoice_quantity_bought,
        'invoice_remark' => $invoice_remark,
        'invoice_date' => $invoice_date,
        ];
        DB::table('invoice')->insert($arr1);

        for($i=0;$i<count($ql_invoice_product_id);$i++){
            $arr2 = [
            'ql_invoices_id' => $ql_invoices_id,
            'ql_invoices_discount' => $ql_invoices_discount,
            'ql_invoices_invoice_id' => $id,
            'ql_invoice_product_id' => $ql_invoice_product_id[$i],
            'ql_invoice_quantity_bought' => $ql_invoice_quantity_bought,

            ];
            DB::table('ql_invoices')->insert($arr2);
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
