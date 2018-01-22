<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTableCustomers extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //
        Schema::create('customers', function(Blueprint $table) {
            $table->increments('customer_id');
            $table->string('customer_fname');
            $table->string('customer_lname');
            $table->string('customer_gender');
            $table->string('customer_email',150)->unique();
            $table->string('customer_city')->nullable();
            $table->string('customer_mobile')->nullable();
            $table->string('customer_telephone')->nullable();
            $table->string('customer_street')->nullable();
            $table->string('customer_address')->nullable();
            $table->string('customer_note')->nullable();
            $table->date('customer_birthday')->nullable();
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
        Schema::dropIfExists('customers');
    }
}
