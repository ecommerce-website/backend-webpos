<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTableProducts extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //
        Schema::create('products',function (Blueprint $table) {
            $table->increments('product_id');
            $table->string('product_type')->default('Regular product');
            $table->string('product_stock_number');
            $table->string('product_name');
            $table->string('product_img')->nullable();
            $table->string('product_description')->nullable();
            $table->string('product_unit_string')->default('PC');
            $table->integer('product_on_hand')->default('0');
            $table->integer('product_cost')->default('0');
            $table->integer('product_retail_price')->default('0');
            $table->integer('product_min_quantity')->default('0');
            $table->integer('product_max_quantity')->default('0');
            $table->integer('product_unit_quantity')->default('1');
            $table->tinyInteger('product_active')->default('1');
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
        Schema::dropIfExists('products');
    }
}
