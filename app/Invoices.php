<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Invoices extends Model
{
    //
    protected $table = 'invoices';
    protected $primaryKey = 'invoice_id';
    public function customers() {
    	return $this->belongsTo('App\Customers','invoice_customer_id','customer_id');
    }
    public function ql_invoices() {
    	return $this->hasMany('App\QLInvoices','ql_invoices_invoice_id','invoice_id');
    }
}
