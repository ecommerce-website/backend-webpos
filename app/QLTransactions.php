<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class QLTransactions extends Model
{
    //
    protected $table = 'ql_transactions';
    public function transactions() {
    	return $this->belongsTo('App\Transactions','ql_transactions_transaction_id','transaction_id');
    }
}
