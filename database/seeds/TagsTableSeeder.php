<?php

use Illuminate\Database\Seeder;
use App\Tags;
use Faker\Factory as Faker;

class TagsTableSeeder extends Seeder
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
        	$tag = Tags::create([
        		'tag_name' => $faker->word
        	]);
        }
    }
}
