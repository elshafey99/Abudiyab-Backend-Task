<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\CreateOrderRequest;
use App\Http\Resources\OrderResource;
use App\Helpers\ApiResponse;
use App\Services\Api\OrderService;
class OrderController extends Controller
{
    public function __construct(
        private OrderService $orderService
    ) {}
    public function store(CreateOrderRequest $request)
    {
        try {
            $order = $this->orderService->createOrder($request->validated());

            return ApiResponse::success(
                new OrderResource($order),
                'Order created successfully',
                201
            );
        } catch (\Exception $e) {
            return ApiResponse::error(
                'Failed to create order',
                500,
                ['exception' => $e->getMessage()]
            );
        }
    }
}
