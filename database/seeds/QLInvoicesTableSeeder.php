<?php

use Illuminate\Database\Seeder;
use App\QLInvoices;
use Faker\Factory as Faker;
class QLInvoicesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //
        for ($i = 0; $i < 30 ; $i++) { 
        	# code...
        	$faker = Faker::create();
        	$QLInvoice = QLInvoices::create([
        		'ql_invoices_invoice_id' => rand(1,30),
        		'ql_invoices_product_id' => rand(1,30),
        		'ql_invoices_discount' => rand(1,10),
        		'ql_invoices_quantity_bought' => rand(1,100),
        		'ql_invoices_line_note' => $faker->word
        	]);
        }
    }
}
