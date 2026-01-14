<?php

namespace App\Services;

use App\Models\Product;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use App\Repositories\Interfaces\ProductRepositoryInterface;

/**
 * Class ProductService
 * @package App\Services
 */
class ProductService
{
    /**
     * @var ProductRepositoryInterface
     */
    protected ProductRepositoryInterface $productRepository;

    /**
     * ProductService constructor.
     *
     * @param ProductRepositoryInterface $productRepository
     */
    public function __construct(ProductRepositoryInterface $productRepository)
    {
        $this->productRepository = $productRepository;
    }

    /**
     * Get all products paginated.
     *
     * @param int $perPage
     * @return LengthAwarePaginator
     */
    public function getAllProducts(int $perPage = 15): LengthAwarePaginator
    {
        return $this->productRepository->getAllPaginated($perPage);
    }

    /**
     * Get a product by ID.
     *
     * @param int $id
     * @return Product|null
     * @throws ModelNotFoundException
     */
    public function getProductById(int $id)
    {
        $product = $this->productRepository->findById($id);
        if (!$product) {
            throw new ModelNotFoundException("Product not found");
        }
        return $product;
    }

    /**
     * Create a new product.
     *
     * @param array $data
     * @return Product
     */
    public function createProduct(array $data)
    {
        return $this->productRepository->create($data);
    }

    /**
     * Update a product.
     *
     * @param int $id
     * @param array $data
     * @return Product|null
     * @throws ModelNotFoundException
     */
    public function updateProduct(int $id, array $data)
    {
        $updated = $this->productRepository->update($id, $data);
        if (!$updated) {
            throw new ModelNotFoundException("Product not found or update failed");
        }
        return $this->productRepository->findById($id);
    }

    /**
     * Delete a product.
     *
     * @param int $id
     * @return bool
     * @throws ModelNotFoundException
     */
    public function deleteProduct(int $id)
    {
        $deleted = $this->productRepository->delete($id);
        if (!$deleted) {
            throw new ModelNotFoundException("Product not found or delete failed");
        }
        return true;
    }

    /**
     * Get prices for a product.
     *
     * @param int $productId
     * @return Collection
     * @throws ModelNotFoundException
     */
    public function getProductPrices(int $productId): Collection
    {
        // Ensure product exists
        $this->getProductById($productId);
        return $this->productRepository->getPrices($productId);
    }

    /**
     * Add a price to a product.
     *
     * @param int $productId
     * @param array $data
     * @return mixed
     * @throws ModelNotFoundException
     */
    public function addProductPrice(int $productId, array $data)
    {
        // Ensure product exists
        $this->getProductById($productId);
        return $this->productRepository->addPrice($productId, $data);
    }
}
