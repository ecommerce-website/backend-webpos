<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Invoices;
use App\QLInvoices;
use Carbon\Carbon;
use DB;

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

        $invoices = new Invoices();
        $invoices->invoice_user_id = $qlinvoice['invoice_user_id'];
        $invoices->invoice_customer_id = $qlinvoice['invoice_customer_id'];
        $invoices->invoice_total = $qlinvoice['invoice_total'];
        $invoices->invoice_quantity_bought = $qlinvoice['invoice_quantity_bought'];
        if(!$invoices->save()){
            return response()->json([
                'error' => [
                    'status' => 1,
                    'message' => 'Lưu hóa đơn gặp lỗi!'
                ]
            ],422);
        }

        $invoice_products = $qlinvoice['invoice_products'];
        $ql_invoices_ids = array();
        foreach($invoice_products as $product){
            $ql_invoices = new QLInvoices();
            $ql_invoices->ql_invoices_invoice_id = $invoices->invoice_id;
            $ql_invoices->ql_invoices_product_id = $product['product_id'];
            $ql_invoices->ql_invoices_product_retail_price = $product['product_retail_price'];
            $ql_invoices->ql_invoices_discount = $product['product_discount'];
            $ql_invoices->ql_invoices_quantity_bought = $product['product_count'];
            $ql_invoices->ql_invoices_line_note = "";
            if($ql_invoices->save()){
                array_push($ql_invoices_ids, $ql_invoices->ql_invoices_id);
            } else {
                foreach($ql_invoices_ids as $id){
                    QLInvoices::find($id)->delete();
                    $invoices->delete();
                }
                return response()->json([
                    'error' => [
                        'status' => 1,
                        'message' => 'Lưu hóa đơn gặp lỗi 2!'
                    ]
                ],422);
            }
        }
        return response()->json(array(
            'invoice_id' => $invoices->invoice_id, 
            'invoice_date' => date_format(date_create(Invoices::find($invoices->invoice_id)->invoice_date), "d-m-Y H:i:s"),
            'success' => true
        ),200);
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
