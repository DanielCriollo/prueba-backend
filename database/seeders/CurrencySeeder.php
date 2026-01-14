<?php

namespace Database\Seeders;

use App\Models\Currency;
use Illuminate\Database\Seeder;

class CurrencySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $currencies = [
            [
                'name' => 'US Dollar',
                'symbol' => '$',
                'exchange_rate' => 1.000000, // Base currency
            ],
            [
                'name' => 'Euro',
                'symbol' => '€',
                'exchange_rate' => 0.920000, // 1 USD = 0.92 EUR
            ],
            [
                'name' => 'British Pound',
                'symbol' => '£',
                'exchange_rate' => 0.790000, // 1 USD = 0.79 GBP
            ],
            [
                'name' => 'Mexican Peso',
                'symbol' => '$',
                'exchange_rate' => 17.500000, // 1 USD = 17.50 MXN
            ],
            [
                'name' => 'Japanese Yen',
                'symbol' => '¥',
                'exchange_rate' => 150.000000, // 1 USD = 150 JPY
            ],
        ];

        foreach ($currencies as $currency) {
            Currency::create($currency);
        }
    }
}
