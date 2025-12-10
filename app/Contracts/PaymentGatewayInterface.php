<?php

namespace App\Contracts;

use App\Models\Order;

/**
 * Payment Gateway Interface
 * 
 * Defines the contract that all payment gateway implementations must follow.
 * This allows easy switching between different payment providers (Paymob, Stripe, PayPal, etc.)
 */
interface PaymentGatewayInterface
{
    /**
     * Authenticate with the payment gateway
     * 
     * @return string Authentication token
     */
    public function authenticate(): string;

    /**
     * Create/Register an order with the payment gateway
     * 
     * @param Order $order The order to register
     * @return array Payment gateway order data (including gateway order ID)
     */
    public function createOrder(Order $order): array;

    /**
     * Generate payment URL/iframe for the customer
     * 
     * @param Order $order The order to generate payment for
     * @param array $billingData Customer billing information
     * @return string Payment URL or iframe URL
     */
    public function generatePaymentUrl(Order $order, array $billingData): string;

    /**
     * Verify payment status with the payment gateway
     * 
     * @param string $transactionId Transaction ID from payment gateway
     * @return array Payment verification data
     */
    public function verifyPayment(string $transactionId): array;
}
