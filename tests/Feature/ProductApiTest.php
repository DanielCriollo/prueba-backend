<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\Product;
use App\Models\Currency;
use App\Models\User;

class ProductApiTest extends TestCase
{
    use RefreshDatabase;

    protected $user;

    protected function setUp(): void
    {
        parent::setUp();
        $this->user = User::factory()->create();
    }

    public function test_can_list_products()
    {
        Currency::factory()->create(['name' => 'USD']);
        Product::factory()->count(5)->create();

        $response = $this->actingAs($this->user, 'api')->getJson('/api/products');

        $response->assertStatus(200)
                 ->assertJsonStructure([
                     'status',
                     'message',
                     'data' => [
                         'data',
                         'links',
                         'meta' => [
                             'current_page',
                             'last_page',
                             'per_page',
                             'total'
                         ]
                     ]
                 ]);
    }

    public function test_can_create_product()
    {
        $currency = Currency::factory()->create(['name' => 'USD']);

        $data = [
            'name' => 'Test Product',
            'description' => 'Test Description',
            'price' => 100.00,
            'currency_id' => $currency->id,
            'tax_cost' => 10.00,
            'manufacturing_cost' => 50.00,
        ];

        $response = $this->actingAs($this->user, 'api')->postJson('/api/products', $data);

        $response->assertStatus(201)
                 ->assertJsonFragment(['name' => 'Test Product']);
        
        $this->assertDatabaseHas('products', ['name' => 'Test Product']);
    }

    public function test_can_show_product()
    {
        $currency = Currency::factory()->create();
        $product = Product::factory()->create(['currency_id' => $currency->id]);

        $response = $this->actingAs($this->user, 'api')->getJson("/api/products/{$product->id}");

        $response->assertStatus(200)
                 ->assertJsonFragment(['id' => $product->id]);
    }

    public function test_can_update_product()
    {
        $currency = Currency::factory()->create();
        $product = Product::factory()->create(['currency_id' => $currency->id]);

        $data = [
            'name' => 'Updated Name',
            'price' => 150.00
        ];

        $response = $this->actingAs($this->user, 'api')->putJson("/api/products/{$product->id}", $data);

        $response->assertStatus(200)
                 ->assertJsonFragment(['name' => 'Updated Name']);
        
        $this->assertDatabaseHas('products', ['id' => $product->id, 'name' => 'Updated Name']);
    }

    public function test_can_delete_product()
    {
        $currency = Currency::factory()->create();
        $product = Product::factory()->create(['currency_id' => $currency->id]);

        $response = $this->actingAs($this->user, 'api')->deleteJson("/api/products/{$product->id}");

        $response->assertStatus(200);
        $this->assertDatabaseMissing('products', ['id' => $product->id]);
    }

    public function test_can_add_product_price()
    {
        $currency1 = Currency::factory()->create(['name' => 'USD']);
        $currency2 = Currency::factory()->create(['name' => 'EUR']);
        $product = Product::factory()->create(['currency_id' => $currency1->id]);

        $data = [
            'currency_id' => $currency2->id,
            'price' => 85.00
        ];

        $response = $this->actingAs($this->user, 'api')->postJson("/api/products/{$product->id}/prices", $data);

        $response->assertStatus(201)
                 ->assertJsonFragment(['price' => 85.00]);

        $this->assertDatabaseHas('product_prices', [
            'product_id' => $product->id,
            'currency_id' => $currency2->id,
            'price' => 85.00
        ]);
    }
}
