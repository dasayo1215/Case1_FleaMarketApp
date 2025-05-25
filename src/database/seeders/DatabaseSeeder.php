<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        $this->call(ProductConditionsSeeder::class);
        $this->call(CategoriesSeeder::class);
        $this->call(PaymentMethodsSeeder::class);
        $this->call(UsersSeeder::class);
        $this->call(ProductsSeeder::class);
    }
}
