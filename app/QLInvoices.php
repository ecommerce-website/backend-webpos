<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class QLInvoices extends Model
{
    //
    protected $table = 'ql_invoices';
    public function products() {
    	return $this->belongsTo('App\Products','ql_invoices_product_id','product_id');
    }
    public function invoices() {
    	return $this->belongsTo('App\Invoices','ql_invoices_invoice_id','invoice_id');
    }
}
