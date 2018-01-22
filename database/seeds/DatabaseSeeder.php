<?php

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // $this->call(UsersTableSeeder::class);
        $this->call([
            UsersTableSeeder::class,
        	ProductsTableSeeder::class,
        	TagsTableSeeder::class,
        	BarcodesTableSeeder::class,
        	CustomersTableSeeder::class,
        	TransactionsTableSeeder::class,
        	InvoicesTableSeeder::class,
        	QLTagsTableSeeder::class,
        	QLTransactionsTableSeeder::class,
        	QLInvoicesTableSeeder::class
        ]);
    }
}
