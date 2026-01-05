<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreProductRequest;
use App\Http\Requests\UpdateProductRequest;
use App\Http\Resources\ProductResource;
use App\Services\ProductService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class ProductController extends Controller
{
    public function __construct(
        private readonly ProductService $productService
    ) {
    }

    public function index(): AnonymousResourceCollection
    {
        $products = $this->productService->getAllProducts();

        return ProductResource::collection($products);
    }

    public function show(int $id): ProductResource|JsonResponse
    {
        $product = $this->productService->getProduct($id);

        if (!$product) {
            return response()->json(['message' => 'Product not found'], 404);
        }

        return new ProductResource($product);
    }

    public function store(StoreProductRequest $request): ProductResource
    {
        $product = $this->productService->createProduct($request->validated());

        return new ProductResource($product);
    }

    public function update(UpdateProductRequest $request, int $id): ProductResource|JsonResponse
    {
        $success = $this->productService->updateProduct($id, $request->validated());

        if (!$success) {
            return response()->json(['message' => 'Product not found'], 404);
        }

        $product = $this->productService->getProduct($id);

        return new ProductResource($product);
    }

    public function destroy(int $id): JsonResponse
    {
        $success = $this->productService->deleteProduct($id);

        if (!$success) {
            return response()->json(['message' => 'Product not found'], 404);
        }

        return response()->json(['message' => 'Product deleted successfully']);
    }
}
