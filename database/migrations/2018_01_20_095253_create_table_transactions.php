<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTableTransactions extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //
        Schema::create('transactions',function(Blueprint $table) {
            $table->increments('transaction_id');
            $table->string('transaction_type');
            $table->string('transaction_user');
            $table->string('transaction_ref');
            $table->string('transaction_parent_ref'); 
            $table->string('transaction_status');           
            $table->string('transaction_remark')->nullable();
            $table->string('transaction_supplier')->nullable();
            $table->string('transaction_related_party')->nullable();
            $table->dateTime('transaction_date');
            $table->timestamps();
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
        Schema::dropIfExists('transactions');
    }
}
