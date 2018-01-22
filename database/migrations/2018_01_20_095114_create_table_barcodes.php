<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTableBarcodes extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //
        Schema::create('barcodes',function(Blueprint $table) {
            $table->increments('barcode_id');
            $table->integer('barcode_product_id')->unsigned();
            $table->string('barcode_name');
            $table->string('barcode_img');
            $table->timestamps();
        });
        Schema::table('barcodes',function(Blueprint $table) {
            $table->foreign('barcode_product_id')->references('product_id')->on('products');
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
        Schema::dropIfExists('barcodes');
    }
}
