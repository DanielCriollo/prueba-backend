<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Resources\Product\ProductResource;
use App\Http\Resources\Product\ProductPriceResource;
use App\Http\Requests\Product\StoreProductPriceRequest;
use App\Http\Requests\Product\StoreProductRequest;
use App\Http\Requests\Product\UpdateProductRequest;
use App\Services\ProductService;
use App\Traits\ApiResponse;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use OpenApi\Attributes as OA;

class ProductController extends Controller
{
    use ApiResponse;

    protected ProductService $productService;

    public function __construct(ProductService $productService)
    {
        $this->productService = $productService;
    }

    #[OA\Get(
        path: "/api/products",
        summary: "Get all products",
        tags: ["Products"],
        security: [["bearerAuth" => []]],
        parameters: [
            new OA\Parameter(
                name: "per_page",
                in: "query",
                description: "Number of items per page",
                required: false,
                schema: new OA\Schema(type: "integer", default: 15)
            )
        ],
        responses: [
            new OA\Response(
                response: 200,
                description: "Successful operation",
                content: new OA\JsonContent(
                    properties: [
                        new OA\Property(property: "status", type: "string", example: "success"),
                        new OA\Property(property: "message", type: "string", example: "Products retrieved successfully"),
                        new OA\Property(
                            property: "data",
                            type: "object",
                            properties: [
                                new OA\Property(property: "data", type: "array", items: new OA\Items(type: "object")),
                                new OA\Property(property: "links", type: "object"),
                                new OA\Property(property: "meta", type: "object")
                            ]
                        )
                    ]
                )
            )
        ]
    )]
    /**
     * Display a listing of products.
     *
     * @param  Request  $request
     * @return JsonResponse
     */
    public function index(Request $request): JsonResponse
    {
        $perPage = $request->input('per_page', 15);
        $products = $this->productService->getAllProducts($perPage);
        $resource = ProductResource::collection($products);
        return $this->successResponse($resource->response()->getData(true), 'Products retrieved successfully');
    }

    #[OA\Post(
        path: "/api/products",
        summary: "Create a new product",
        tags: ["Products"],
        security: [["bearerAuth" => []]],
        requestBody: new OA\RequestBody(
            required: true,
            content: new OA\JsonContent(
                required: ["name", "description", "price", "currency_id", "tax_cost", "manufacturing_cost"],
                properties: [
                    new OA\Property(property: "name", type: "string", example: "Product Name"),
                    new OA\Property(property: "description", type: "string", example: "Product Description"),
                    new OA\Property(property: "price", type: "number", format: "float", example: 100.00),
                    new OA\Property(property: "currency_id", type: "integer", example: 1),
                    new OA\Property(property: "tax_cost", type: "number", format: "float", example: 10.00),
                    new OA\Property(property: "manufacturing_cost", type: "number", format: "float", example: 50.00)
                ]
            )
        ),
        responses: [
            new OA\Response(
                response: 201,
                description: "Product created successfully",
                content: new OA\JsonContent(
                    properties: [
                        new OA\Property(property: "status", type: "string", example: "success"),
                        new OA\Property(property: "message", type: "string", example: "Product created successfully"),
                        new OA\Property(property: "data", type: "object")
                    ]
                )
            ),
            new OA\Response(
                response: 422,
                description: "Validation Error"
            )
        ]
    )]
    /**
     * Store a newly created product in storage.
     *
     * @param  StoreProductRequest  $request
     * @return JsonResponse
     */
    public function store(StoreProductRequest $request): JsonResponse
    {
        try {
            $product = $this->productService->createProduct($request->validated());
            return $this->successResponse(new ProductResource($product), 'Product created successfully', 201);
        } catch (\Exception $e) {
            return $this->errorResponse('Error creating product: ' . $e->getMessage(), 500);
        }
    }

    #[OA\Get(
        path: "/api/products/{id}",
        summary: "Get a product by ID",
        tags: ["Products"],
        security: [["bearerAuth" => []]],
        parameters: [
            new OA\Parameter(
                name: "id",
                in: "path",
                description: "Product ID",
                required: true,
                schema: new OA\Schema(type: "integer")
            )
        ],
        responses: [
            new OA\Response(
                response: 200,
                description: "Product retrieved successfully",
                content: new OA\JsonContent(
                    properties: [
                        new OA\Property(property: "status", type: "string", example: "success"),
                        new OA\Property(property: "message", type: "string", example: "Product retrieved successfully"),
                        new OA\Property(property: "data", type: "object")
                    ]
                )
            ),
            new OA\Response(
                response: 404,
                description: "Product not found"
            )
        ]
    )]
    /**
     * Display the specified product.
     *
     * @param  int  $id
     * @return JsonResponse
     */
    public function show($id): JsonResponse
    {
        try {
            $product = $this->productService->getProductById($id);
            return $this->successResponse(new ProductResource($product), 'Product retrieved successfully');
        } catch (ModelNotFoundException $e) {
            return $this->errorResponse('Product not found', 404);
        }
    }

    #[OA\Put(
        path: "/api/products/{id}",
        summary: "Update a product",
        tags: ["Products"],
        security: [["bearerAuth" => []]],
        parameters: [
            new OA\Parameter(
                name: "id",
                in: "path",
                description: "Product ID",
                required: true,
                schema: new OA\Schema(type: "integer")
            )
        ],
        requestBody: new OA\RequestBody(
            required: true,
            content: new OA\JsonContent(
                properties: [
                    new OA\Property(property: "name", type: "string", example: "Updated Product Name"),
                    new OA\Property(property: "description", type: "string", example: "Updated Description"),
                    new OA\Property(property: "price", type: "number", format: "float", example: 120.00),
                    new OA\Property(property: "currency_id", type: "integer", example: 1),
                    new OA\Property(property: "tax_cost", type: "number", format: "float", example: 12.00),
                    new OA\Property(property: "manufacturing_cost", type: "number", format: "float", example: 55.00)
                ]
            )
        ),
        responses: [
            new OA\Response(
                response: 200,
                description: "Product updated successfully",
                content: new OA\JsonContent(
                    properties: [
                        new OA\Property(property: "status", type: "string", example: "success"),
                        new OA\Property(property: "message", type: "string", example: "Product updated successfully"),
                        new OA\Property(property: "data", type: "object")
                    ]
                )
            ),
            new OA\Response(
                response: 404,
                description: "Product not found"
            )
        ]
    )]
    /**
     * Update the specified product in storage.
     *
     * @param  UpdateProductRequest  $request
     * @param  int  $id
     * @return JsonResponse
     */
    public function update(UpdateProductRequest $request, $id): JsonResponse
    {
        try {
            $product = $this->productService->updateProduct($id, $request->validated());
            return $this->successResponse(new ProductResource($product), 'Product updated successfully');
        } catch (ModelNotFoundException $e) {
            return $this->errorResponse('Product not found', 404);
        } catch (\Exception $e) {
            return $this->errorResponse('Error updating product: ' . $e->getMessage(), 500);
        }
    }

    #[OA\Delete(
        path: "/api/products/{id}",
        summary: "Delete a product",
        tags: ["Products"],
        security: [["bearerAuth" => []]],
        parameters: [
            new OA\Parameter(
                name: "id",
                in: "path",
                description: "Product ID",
                required: true,
                schema: new OA\Schema(type: "integer")
            )
        ],
        responses: [
            new OA\Response(
                response: 200,
                description: "Product deleted successfully",
                content: new OA\JsonContent(
                    properties: [
                        new OA\Property(property: "status", type: "string", example: "success"),
                        new OA\Property(property: "message", type: "string", example: "Product deleted successfully"),
                        new OA\Property(property: "data", type: "null")
                    ]
                )
            ),
            new OA\Response(
                response: 404,
                description: "Product not found"
            )
        ]
    )]
    /**
     * Remove the specified product from storage.
     *
     * @param  int  $id
     * @return JsonResponse
     */
    public function destroy($id): JsonResponse
    {
        try {
            $this->productService->deleteProduct($id);
            return $this->successResponse(null, 'Product deleted successfully');
        } catch (ModelNotFoundException $e) {
            return $this->errorResponse('Product not found', 404);
        } catch (\Exception $e) {
            return $this->errorResponse('Error deleting product: ' . $e->getMessage(), 500);
        }
    }

    #[OA\Get(
        path: "/api/products/{id}/prices",
        summary: "Get product prices",
        tags: ["Products"],
        security: [["bearerAuth" => []]],
        parameters: [
            new OA\Parameter(
                name: "id",
                in: "path",
                description: "Product ID",
                required: true,
                schema: new OA\Schema(type: "integer")
            )
        ],
        responses: [
            new OA\Response(
                response: 200,
                description: "Product prices retrieved successfully",
                content: new OA\JsonContent(
                    properties: [
                        new OA\Property(property: "status", type: "string", example: "success"),
                        new OA\Property(property: "message", type: "string", example: "Product prices retrieved successfully"),
                        new OA\Property(property: "data", type: "array", items: new OA\Items(type: "object"))
                    ]
                )
            ),
            new OA\Response(
                response: 404,
                description: "Product not found"
            )
        ]
    )]
    /**
     * Get the price history for a specific product.
     *
     * @param  int  $id
     * @return JsonResponse
     */
    public function getPrices($id): JsonResponse
    {
        try {
            $prices = $this->productService->getProductPrices($id);
            return $this->successResponse(ProductPriceResource::collection($prices), 'Product prices retrieved successfully');
        } catch (ModelNotFoundException $e) {
            return $this->errorResponse('Product not found', 404);
        }
    }

    #[OA\Post(
        path: "/api/products/{id}/prices",
        summary: "Add a price to a product",
        tags: ["Products"],
        security: [["bearerAuth" => []]],
        parameters: [
            new OA\Parameter(
                name: "id",
                in: "path",
                description: "Product ID",
                required: true,
                schema: new OA\Schema(type: "integer")
            )
        ],
        requestBody: new OA\RequestBody(
            required: true,
            content: new OA\JsonContent(
                required: ["currency_id", "price"],
                properties: [
                    new OA\Property(property: "currency_id", type: "integer", example: 2),
                    new OA\Property(property: "price", type: "number", format: "float", example: 85.00)
                ]
            )
        ),
        responses: [
            new OA\Response(
                response: 201,
                description: "Product price added successfully",
                content: new OA\JsonContent(
                    properties: [
                        new OA\Property(property: "status", type: "string", example: "success"),
                        new OA\Property(property: "message", type: "string", example: "Product price added successfully"),
                        new OA\Property(property: "data", type: "object")
                    ]
                )
            ),
            new OA\Response(
                response: 404,
                description: "Product not found"
            )
        ]
    )]
    /**
     * Add a new price to a specific product.
     *
     * @param  StoreProductPriceRequest  $request
     * @param  int  $id
     * @return JsonResponse
     */
    public function storePrice(StoreProductPriceRequest $request, $id): JsonResponse
    {
        try {
            $price = $this->productService->addProductPrice($id, $request->validated());
            return $this->successResponse(new ProductPriceResource($price), 'Product price added successfully', 201);
        } catch (ModelNotFoundException $e) {
            return $this->errorResponse('Product not found', 404);
        } catch (\Exception $e) {
            return $this->errorResponse('Error adding product price: ' . $e->getMessage(), 500);
        }
    }
}
