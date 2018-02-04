<?php

use Illuminate\Database\Seeder;
use App\Transactions;
use Faker\Factory as Faker;

class TransactionsTableSeeder extends Seeder
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
        	$transaction = Transactions::create([
        		'transaction_type' => 'Normal',
        		'transaction_user' => $faker->username,
        		'transaction_ref' => $faker->word,
        		'transaction_parent_ref' => $faker->word,
        		'transaction_status' => 'Posted',
        		'transaction_remark' => $faker->sentence($nbWords = 6,$variableNbWords = true),
        		'transaction_supplier' => $faker->company,
        		'transaction_related_party' => $faker->username,
        		'transaction_date' => $faker->dateTime($max = 'now',$timezone = null),
        	]);
        }
    }
}
