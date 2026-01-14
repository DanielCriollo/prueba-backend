<?php

namespace Database\Factories;

use App\Models\Currency;
use App\Models\Product;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Product>
 */
class ProductFactory extends Factory
{
    protected $model = Product::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $price = $this->faker->randomFloat(2, 10, 1000);
        
        return [
            'name' => ucwords($this->faker->words(3, true)),
            'description' => $this->faker->text(200),
            'price' => $price,
            'currency_id' => Currency::inRandomOrder()->first()?->id ?? Currency::factory(),
            'tax_cost' => $price * 0.16, // Assuming 16% tax
            'manufacturing_cost' => $price * 0.40, // Assuming 40% manufacturing cost
        ];
    }
}
