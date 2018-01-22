<?php

use Illuminate\Database\Seeder;
use Faker\Factory as Faker;
use App\Users;

class UsersTableSeeder extends Seeder
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
        	# code...
        	$user = Users::create([
	        	'user_name' => $faker->username,
	        	'user_email' => $faker->email,
	        	'user_pass' => $faker->word,
	        	'user_company_name' => $faker->company,
	        	'user_owner_name' => $faker->name,
	        	'user_country' => $faker->country
	        ]);
        }
    }
}
