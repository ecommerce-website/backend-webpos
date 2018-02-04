<?php

use Illuminate\Database\Seeder;

class CustomerTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $faker = Faker\Factory::create();
 
        foreach(range(1,10) as $index)
        {
            Customer::create([                
                'customer_id' =>$faker->numberBetween($min = 1, $max = 5)
                'customer_group_id' =>$faker->numberBetween($min = 1, $max = 5)
                'customer_fname' =>$faker->paragraph($nbSentences = 3),
            ]);
        }
    }
}
