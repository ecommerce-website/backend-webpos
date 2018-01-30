<?php

use Illuminate\Database\Seeder;
use App\QLTransactions;

class QLTransactionsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //
        for($i = 0;$i < 30;$i++) {
        	$QLTransaction = QLTransactions::create([
        		'ql_transactions_product_id' => rand(1,30),
        		'ql_transactions_transaction_id' => rand(1,30),
        		'ql_transactions_quantity_bought' => rand(1,100),
        		'ql_transactions_discount' => rand(0,50)
        	]);
        }
    }
}
