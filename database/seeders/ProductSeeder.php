<?php

namespace Database\Seeders;

use App\Models\Currency;
use App\Models\Product;
use App\Models\ProductPrice;
use Illuminate\Database\Seeder;

class ProductSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Ensure we have currencies
        if (Currency::count() === 0) {
            $this->call(CurrencySeeder::class);
        }

        $currencies = Currency::all();
        $baseCurrency = $currencies->firstWhere('name', 'US Dollar') ?? $currencies->first();

        // Create 20 products
        Product::factory()->count(20)->create()->each(function (Product $product) use ($currencies) {
            
            // For each product, calculate prices in all other currencies
            foreach ($currencies as $currency) {
                // If the product's currency is the same as this currency, we might skip or store it if needed.
                // The prompt "Precios de productos" implies storing specific prices for currencies.
                // Usually the main price is in `products` table, and `product_prices` has others.
                // Or `product_prices` has ALL prices. Let's assume `product_prices` stores prices for specific currencies.
                
                // If product's base currency is different from current loop currency, we calculate.
                // Or we just store ALL prices in product_prices for easier lookup.
                
                // Let's Calculate price based on exchange rates.
                // Price in Target Currency = (Price in Base Product Currency / Base Product Currency Rate) * Target Currency Rate
                // Assuming exchange_rate is relative to a common base (e.g. USD = 1).
                
                $productBaseCurrency = $product->currency;
                
                // Convert product price to USD (or whatever reference 1.0 is)
                $priceInRef = $product->price / $productBaseCurrency->exchange_rate;
                
                // Convert Ref price to Target Currency
                $priceInTarget = $priceInRef * $currency->exchange_rate;
                
                ProductPrice::create([
                    'product_id' => $product->id,
                    'currency_id' => $currency->id,
                    'price' => round($priceInTarget, 2),
                ]);
            }
        });
    }
}
