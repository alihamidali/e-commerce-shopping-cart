<?php

namespace App\Http\Controllers;

use App\Http\Resources\OrderResource;
use App\Services\OrderService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class OrderController extends Controller
{
    public function __construct(
        private readonly OrderService $orderService
    ) {
    }

    public function index(Request $request): AnonymousResourceCollection
    {
        $orders = $this->orderService->getUserOrders($request->user()->id);

        return OrderResource::collection($orders);
    }

    public function show(int $id): OrderResource|JsonResponse
    {
        $order = $this->orderService->getOrder($id);

        if (!$order) {
            return response()->json(['message' => 'Order not found'], 404);
        }

        return new OrderResource($order);
    }

    public function store(Request $request): OrderResource|JsonResponse
    {
        try {
            $order = $this->orderService->createOrderFromCart($request->user()->id);

            if (!$order) {
                return response()->json(['message' => 'Cart is empty'], 422);
            }

            return new OrderResource($order->load('orderItems.product'));
        } catch (\Exception $e) {
            return response()->json(['message' => $e->getMessage()], 422);
        }
    }
}
