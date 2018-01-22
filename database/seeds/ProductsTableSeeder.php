<?php

use Illuminate\Database\Seeder;
use Faker\Factory as Faker;
use App\Products;
class ProductsTableSeeder extends Seeder
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
        	$product = Products::create([
               'product_type' => 'Regular product',
        	   'product_stock_number' => rand(1,31),
        	   'product_name' => $faker->word,
        	   'product_img' => $faker->imageUrl($width = 640,$height = 480),
               'product_description' => $faker->word,
        	   'product_unit_string' => $faker->word,
        	   'product_on_hand' => rand(0,1000),
        	   'product_cost' => rand(1,100),
        	   'product_retail_price' => rand(1,100),
        	   'product_min_quantity' => rand(1,100),
        	   'product_max_quantity' => rand(1,100),
        	   'product_unit_quantity' => rand(1,10),
        	   'product_active' => rand(0,1)
        	]);
        }
    }
}
