<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Transactions extends Model
{
    //
    protected $table = 'transactions';
    protected $primaryKey = 'transaction_id';
    public function qltransactions() {
    	return $this->hasMany('App\QLTransactions','ql_transactions_transaction_id','transaction_id');
    }
}
