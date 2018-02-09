<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTableQLTransactions extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //
        Schema::create('ql_transactions', function (Blueprint $table) {
            $table->increments('ql_transactions_id');
            $table->integer('ql_transactions_product_id')->unsigned();
            $table->integer('ql_transactions_transaction_id')->unsigned();
            $table->integer('ql_transactions_quantity_bought');
            $table->integer('ql_transactions_discount');
            $table->timestamps();
        });
        Schema::table('ql_transactions', function(Blueprint $table) {
            $table->foreign('ql_transactions_transaction_id')->references('transaction_id')->on('transactions')->onDelete('cascade');
            $table->foreign('ql_transactions_product_id')->references('product_id')->on('products')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //
        Schema::dropIfExists('ql_transactions');
    }
}
