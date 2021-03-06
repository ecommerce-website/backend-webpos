<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Products extends Model
{
    //
    protected $table = 'products';
    public function barcodes() {
    	return $this->hasMany('App\Barcodes','barcode_product_id','product_id');
    }
    public function qltags() {
    	return $this->hasMany('App\QLTags','ql_tags_product_id','product_id');
    }
    public function qlinvoices() {
    	return $this->hasMany('App\QLInvoices','ql_invoices_product_id','product_id');
    }
    public function qltransactions() {
    	return $this->hasMany('App\QLTransactions','ql_transactions_product_id','product_id');
    }
}
