<?php

namespace App\Services\Api;

use App\Contracts\PaymentGatewayInterface;
use App\Models\Order;
use App\Models\Payment;

/**
 * Payment Service
 * 
 * Gateway-agnostic service for handling payment operations
 */
class PaymentService
{
    public function __construct(
        private PaymentGatewayInterface $paymentGateway
    ) {}

    /**
     * Initiate payment for an order
     * 
     * @param Order $order
     * @param array $billingData Optional billing data
     * @return Payment Payment model instance
     */
    public function initiatePayment(Order $order, array $billingData = []): Payment
    {
        try {
            // Generate payment URL from the gateway
            $paymentUrl = $this->paymentGateway->generatePaymentUrl($order, $billingData);

            // Create payment record in database
            $payment = Payment::create([
                'order_id' => $order->id,
                'payment_gateway' => 'paymob',
                'amount' => $order->amount,
                'status' => 'pending',
                'metadata' => [
                    'payment_url' => $paymentUrl,
                    'initiated_at' => now()->toDateTimeString(),
                ],
            ]);

            return $payment;
        } catch (\Exception $e) {
            throw new \Exception('Payment initiation failed: ' . $e->getMessage());
        }
    }
}
