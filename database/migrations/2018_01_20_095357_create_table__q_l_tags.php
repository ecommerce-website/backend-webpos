<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTableQLTags extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //
        Schema::create('ql_tags', function (Blueprint $table) {
            $table->increments('ql_tags_id');
            $table->integer('ql_tags_product_id')->unsigned();
            $table->integer('ql_tags_tag_id')->unsigned();
            $table->timestamps();
        });
        Schema::table('ql_tags', function(Blueprint $table) {
            $table->foreign('ql_tags_product_id')->references('product_id')->on('products')->onDelete('cascade');
            $table->foreign('ql_tags_tag_id')->references('tag_id')->on('tags')->onDelete('cascade');
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
        Schema::dropIfExists('ql_tags');
    }
}
