<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class QLTransactions extends Model
{
    //
    protected $table = 'ql_transactions';
    protected $primaryKey = 'ql_transactions_id';
    public function transactions() {
    	return $this->belongsTo('App\Transactions','ql_transactions_transaction_id','transaction_id');
    }
    public function products() {
    	return $this->belongsTo('App\Products','ql_transactions_product_id','product_id');
    }
}
