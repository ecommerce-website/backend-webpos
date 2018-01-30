<?php

use Illuminate\Database\Seeder;
use Faker\Factory as Faker;
use App\Customers;

class CustomersTableSeeder extends Seeder
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
        for ($i=0; $i < 30; $i++) { 
        	# code...
        	$customer = Customers::create([
        		'customer_fname' => $faker->firstName,
        		'customer_lname' => $faker->lastName,
        		'customer_gender' => $faker->title,
        		'customer_email' => $faker->email,
        		'customer_city' => $faker->city,
        		'customer_mobile' => '100000',
        		'customer_telephone' => '20000000',
        		'customer_street' => $faker->streetName,
        		'customer_address' => $faker->address,
        		'customer_note' => $faker->sentence($nbWords = 6,$variableNbWords = true),
        		'customer_birthday' => $faker->dateTime($max = 'now',$timezone = null)
        	]);
        }
    }
}
