<?php

namespace App\Repositories;

use App\Models\Product;
use App\Models\ProductPrice;
use App\Repositories\Interfaces\ProductRepositoryInterface;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Collection;

/**
 * Class EloquentProductRepository
 * @package App\Repositories
 */
class EloquentProductRepository implements ProductRepositoryInterface
{
    /**
     * Get all products paginated.
     *
     * @param int $perPage
     * @return LengthAwarePaginator
     */
    public function getAllPaginated(int $perPage = 15): LengthAwarePaginator
    {
        return Product::with('currency')->paginate($perPage);
    }

    /**
     * Find a product by its ID.
     *
     * @param int $id
     * @return Product|null
     */
    public function findById(int $id): ?Product
    {
        return Product::with(['currency', 'prices.currency'])->find($id);
    }

    /**
     * Create a new product.
     *
     * @param array $data
     * @return Product
     */
    public function create(array $data): Product
    {
        return Product::create($data);
    }

    /**
     * Update an existing product.
     *
     * @param int $id
     * @param array $data
     * @return bool
     */
    public function update(int $id, array $data): bool
    {
        $product = Product::find($id);
        if (!$product) {
            return false;
        }
        return $product->update($data);
    }

    /**
     * Delete a product.
     *
     * @param int $id
     * @return bool
     */
    public function delete(int $id): bool
    {
        $product = Product::find($id);
        if (!$product) {
            return false;
        }
        return $product->delete();
    }

    /**
     * Get prices for a specific product.
     *
     * @param int $productId
     * @return Collection
     */
    public function getPrices(int $productId): Collection
    {
        return ProductPrice::where('product_id', $productId)->with('currency')->get();
    }

    /**
     * Add a price to a product.
     *
     * @param int $productId
     * @param array $data
     * @return mixed
     */
    public function addPrice(int $productId, array $data): mixed
    {
        $data['product_id'] = $productId;
        return ProductPrice::create($data);
    }
}
