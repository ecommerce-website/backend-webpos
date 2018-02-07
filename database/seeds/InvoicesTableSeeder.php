<?php

use Illuminate\Database\Seeder;
use Faker\Factory as Faker;
use App\Invoices;
class InvoicesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //
        $faker = Faker::create();

        for ($i = 0;$i < 30;$i++) {
        	$invoice = Invoices::create([
        		'invoice_customer_id' => rand(1,30),
                'invoice_user_id' => rand(1,30),
        		'invoice_total' => rand(1,100),
        		'invoice_quantity_bought' => rand(1,100),
        		'invoice_transaction_type' => $faker->word,
        		'invoice_ref' => $faker->word,
        		'invoice_remark' => $faker->sentence($nbWord = 6,$variableNbWord = true),
        		'invoice_payment_term' => $faker->creditCardType,
        		'invoice_status' => 'Posted',
        		'invoice_date' => $faker->dateTime($max = 'now',$timezone = null)
        	]);
        }
    }
}
