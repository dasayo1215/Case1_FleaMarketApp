<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class PaymentMethodsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $names = [
            "コンビニ払い",
            "カード支払い"
        ];

        foreach ($names as $name) {
            DB::table('payment_methods')->insert([
                'name' => $name,
            ]);
        }
    }
}
