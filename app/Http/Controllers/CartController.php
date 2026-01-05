<?php

namespace App\Http\Controllers;

use App\Http\Requests\AddToCartRequest;
use App\Http\Requests\UpdateCartItemRequest;
use App\Http\Resources\CartItemResource;
use App\Services\CartService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class CartController extends Controller
{
    public function __construct(
        private readonly CartService $cartService
    ) {
    }

    public function index(Request $request): AnonymousResourceCollection
    {
        $cartItems = $this->cartService->getUserCart($request->user()->id);

        return CartItemResource::collection($cartItems);
    }

    public function store(AddToCartRequest $request): CartItemResource|JsonResponse
    {
        $cartItem = $this->cartService->addToCart(
            $request->user()->id,
            $request->validated('product_id'),
            $request->validated('quantity')
        );

        if (!$cartItem) {
            return response()->json([
                'message' => 'Product out of stock or insufficient quantity available'
            ], 422);
        }

        return new CartItemResource($cartItem->load('product'));
    }

    public function update(UpdateCartItemRequest $request, int $id): CartItemResource|JsonResponse
    {
        $success = $this->cartService->updateCartItemQuantity(
            $id,
            $request->validated('quantity')
        );

        if (!$success) {
            return response()->json([
                'message' => 'Failed to update cart item. Item not found or insufficient stock.'
            ], 422);
        }

        $cartItems = $this->cartService->getUserCart($request->user()->id);
        $updatedItem = $cartItems->firstWhere('id', $id);

        return new CartItemResource($updatedItem);
    }

    public function destroy(int $id): JsonResponse
    {
        $success = $this->cartService->removeFromCart($id);

        if (!$success) {
            return response()->json(['message' => 'Cart item not found'], 404);
        }

        return response()->json(['message' => 'Item removed from cart']);
    }

    public function clear(Request $request): JsonResponse
    {
        $this->cartService->clearCart($request->user()->id);

        return response()->json(['message' => 'Cart cleared successfully']);
    }

    public function total(Request $request): JsonResponse
    {
        $total = $this->cartService->calculateCartTotal($request->user()->id);

        return response()->json(['total' => number_format($total, 2, '.', '')]);
    }
}
