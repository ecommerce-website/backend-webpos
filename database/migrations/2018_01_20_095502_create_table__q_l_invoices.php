<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTableQLInvoices extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //
        Schema::create('ql_invoices', function (Blueprint $table) {
            $table->increments('ql_invoices_id');
            $table->integer('ql_invoices_invoice_id')->unsigned();
            $table->integer('ql_invoices_product_id')->unsigned();
            $table->integer('ql_invoices_discount');
            $table->integer('ql_invoices_quantity_bought');
            $table->string('ql_invoices_line_note')->nullable();
            $table->timestamps();
        });
        Schema::table('ql_invoices', function(Blueprint $table) {
            $table->foreign('ql_invoices_invoice_id')->references('invoice_id')->on('invoices')->onDelete('cascade');
            $table->foreign('ql_invoices_product_id')->references('product_id')->on('products')->onDelete('cascade');
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
        Schema::dropIfExists('ql_invoices');
    }
}
