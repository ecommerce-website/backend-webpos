<?php

use Illuminate\Database\Seeder;
use Faker\Factory as Faker;
use App\Barcodes;
class BarcodesTableSeeder extends Seeder
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
        	$barcode = Barcodes::create([
                'barcode_product_id' => rand(1,30),
        		'barcode_name' => $faker->word,
        		'barcode_img' => $faker->imageUrl($width = 640,$height = 480)
        	]);
        }
    }
}
