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
        return response()->json($this->transformCollection($invoice),200);
    }
    public function transformCollection($invoice) {
        $invoiceToArr = $invoice->toArray();
        return [
            'invoice_id' => $invoiceToArr['invoice_id'],
            'invoice_date' => $invoiceToArr['created_at'],
            'invoice_ref' => $invoiceToArr['invoice_ref'],
            'invoice_remark' => $invoiceToArr['invoice_remark'],
            'invoice_payment' => $invoiceToArr['invoice_payment_term'],
            'invoice_customer' => $this->collectCustomer($invoiceToArr['customers']),
            'invoice_products' => $this->collectQLInvoice($invoiceToArr['ql_invoices'])
        ];
    }
    public function collectCustomer($customer) {
        $cus = new class {};
        $cus->customer_id = $customer['customer_id'];
        $cus->customer_fname = $customer['customer_fname'];
        $cus->customer_lname = $customer['customer_lname'];
        $cus->customer_gender = $customer['customer_gender'];
        return $cus;
    }
    public function collectQLInvoice($ql_invoices) {
        $inv = [];
        for ($i = 0;$i < count($ql_invoices);$i++) {
            $obj = new class{};
            $obj->ql_invoices_id = $ql_invoices[$i]['ql_invoices_id'];
            $obj->ql_invoices_line_note = $ql_invoices[$i]['ql_invoices_line_note'];
            $obj->quantity_bought = $ql_invoices[$i]['ql_invoices_quantity_bought'];
            $obj->discount = $ql_invoices[$i]['ql_invoices_discount'];
            $obj->products = $this->collectProduct($ql_invoices[$i]['products']);
            array_push($inv, $obj);
        }
        return $inv;
    }
    public function collectProduct($product) {
        $pro = new class {};
        $pro->product_id = $product['product_id'];
        $pro->product_stock_number = $product['product_stock_number'];
        $pro->product_name = $product['product_name'];
        $pro->product_price = $product['product_retail_price'];
        return $pro;
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
