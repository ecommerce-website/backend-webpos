<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTableInvoices extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //
        Schema::create('invoices',function (Blueprint $table) {
            $table->increments('invoice_id');
            $table->integer('invoice_user_id');
            $table->integer('invoice_customer_id')->unsigned();
            $table->integer('invoice_total')->default('0');
            $table->integer('invoice_quantity_bought')->default('0');
            $table->string('invoice_transaction_type');
            $table->string('invoice_ref');
            $table->string('invoice_remark')->nullable();
            $table->string('invoice_payment_term');
            $table->string('invoice_status');
            $table->dateTime('invoice_date');
            $table->timestamps();
        });

        Schema::table('invoices',function (Blueprint $table) {
            $table->foreign('invoice_customer_id')->references('customer_id')->on('customers')->onDelete('cascade');
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
        Schema::dropIfExists('invoices');
    }
}
