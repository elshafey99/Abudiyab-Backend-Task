<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\InitiatePaymentRequest;
use App\Http\Resources\PaymentResource;
use App\Helpers\ApiResponse;
use App\Models\Order;
use App\Services\Api\PaymentService;
class PaymentController extends Controller
{
    public function __construct(
        private PaymentService $paymentService
    ) {}

    public function initiate(InitiatePaymentRequest $request)
    {
        try {
            $order =Order::findOrFail($request->order_id);

            // Optional billing data
            $billingData = [
                'first_name' => $request->input('first_name', 'Customer'),
                'last_name' => $request->input('last_name', 'Name'),
                'phone_number' => $request->input('phone_number', 'NA'),
            ];

            $payment = $this->paymentService->initiatePayment($order, $billingData);

            return ApiResponse::success(
                new PaymentResource($payment),
                'Payment initiated successfully',
                200
            );
        } catch (\Exception $e) {
            return ApiResponse::error(
                'Failed to initiate payment',
                500,
                ['exception' => $e->getMessage()]
            );
        }
    }
}
