<?php

namespace App\Repositories\Interfaces;

use App\Models\Product;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Collection;

/**
 * Interface ProductRepositoryInterface
 * @package App\Repositories\Interfaces
 */
interface ProductRepositoryInterface
{
    /**
     * Get all products paginated.
     *
     * @param int $perPage Number of items per page.
     * @return LengthAwarePaginator
     */
    public function getAllPaginated(int $perPage = 15): LengthAwarePaginator;

    /**
     * Find a product by its ID.
     *
     * @param int $id Product ID.
     * @return Product|null
     */
    public function findById(int $id): ?Product;

    /**
     * Create a new product.
     *
     * @param array $data Product data.
     * @return Product
     */
    public function create(array $data): Product;

    /**
     * Update an existing product.
     *
     * @param int $id Product ID.
     * @param array $data Data to update.
     * @return bool True if updated, false otherwise.
     */
    public function update(int $id, array $data): bool;

    /**
     * Delete a product.
     *
     * @param int $id Product ID.
     * @return bool True if deleted, false otherwise.
     */
    public function delete(int $id): bool;

    /**
     * Get prices for a specific product.
     *
     * @param int $productId Product ID.
     * @return Collection
     */
    public function getPrices(int $productId): Collection;

    /**
     * Add a price to a product.
     *
     * @param int $productId Product ID.
     * @param array $data Price data.
     * @return mixed
     */
    public function addPrice(int $productId, array $data): mixed;
}
