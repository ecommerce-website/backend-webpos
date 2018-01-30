<?php

use Illuminate\Database\Seeder;
use App\QLTags;
class QLTagsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //
        for ($i=0; $i < 30; $i++) { 
        	# code...
        	$QLTag = QLTags::create([
        		'ql_tags_product_id' => rand(1,30),
        		'ql_tags_tag_id' => rand(1,30)
        	]);
        }
    }
}
