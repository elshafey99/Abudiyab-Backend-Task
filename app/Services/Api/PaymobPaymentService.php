<?php

namespace App\Services\Api;

use App\Contracts\PaymentGatewayInterface;
use App\Models\Order;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

/**
 * Paymob Payment Service
 * 
 * Implements PaymentGatewayInterface for Paymob payment gateway
 */
class PaymobPaymentService implements PaymentGatewayInterface
{
    private ?string $authToken = null;

    /**
     * Authenticate with Paymob API
     * 
     * @return string Authentication token
     */
    public function authenticate(): string
    {
        if ($this->authToken) {
            return $this->authToken;
        }

        try {
            $response = Http::post(config('paymob.urls.auth'), [
                'api_key' => config('paymob.api_key'),
            ]);

            if ($response->successful()) {
                $this->authToken = $response->json('token');
                return $this->authToken;
            }

            throw new \Exception('Paymob authentication failed: ' . $response->body());
        } catch (\Exception $e) {
            Log::error('Paymob authentication error: ' . $e->getMessage());
            throw $e;
        }
    }

    /**
     * Create/Register an order with Paymob
     * 
     * @param Order $order
     * @return array Paymob order data
     */
    public function createOrder(Order $order): array
    {
        try {
            $authToken = $this->authenticate();

            $response = Http::post(config('paymob.urls.order'), [
                'auth_token' => $authToken,
                'delivery_needed' => false,
                'amount_cents' => $order->amount * 100, // Convert to cents
                'currency' => $order->currency,
                'items' => [],
            ]);

            if ($response->successful()) {
                return $response->json();
            }

            throw new \Exception('Paymob order creation failed: ' . $response->body());
        } catch (\Exception $e) {
            Log::error('Paymob order creation error: ' . $e->getMessage());
            throw $e;
        }
    }

    /**
     * Generate payment URL/iframe for the customer
     * 
     * @param Order $order
     * @param array $billingData Customer billing information
     * @return string Payment iframe URL
     */
    public function generatePaymentUrl(Order $order, array $billingData): string
    {
        try {
            $authToken = $this->authenticate();
            $paymobOrder = $this->createOrder($order);

            // Generate payment key
            $paymentKeyResponse = Http::post(config('paymob.urls.payment_key'), [
                'auth_token' => $authToken,
                'amount_cents' => $order->amount * 100,
                'expiration' => 3600, // 1 hour
                'order_id' => $paymobOrder['id'],
                'billing_data' => array_merge([
                    'apartment' => 'NA',
                    'email' => $order->customer_email,
                    'floor' => 'NA',
                    'first_name' => $billingData['first_name'] ?? 'Customer',
                    'street' => 'NA',
                    'building' => 'NA',
                    'phone_number' => $billingData['phone_number'] ?? 'NA',
                    'shipping_method' => 'NA',
                    'postal_code' => 'NA',
                    'city' => 'NA',
                    'country' => 'NA',
                    'last_name' => $billingData['last_name'] ?? 'Name',
                    'state' => 'NA',
                ], $billingData),
                'currency' => $order->currency,
                'integration_id' => config('paymob.integration_id'),
            ]);

            if ($paymentKeyResponse->successful()) {
                $paymentToken = $paymentKeyResponse->json('token');
                $iframeId = config('paymob.iframe_id');
                
                return config('paymob.urls.iframe') . '/' . $iframeId . '?payment_token=' . $paymentToken;
            }

            throw new \Exception('Paymob payment key generation failed: ' . $paymentKeyResponse->body());
        } catch (\Exception $e) {
            Log::error('Paymob payment URL generation error: ' . $e->getMessage());
            throw $e;
        }
    }

    /**
     * Verify payment status with Paymob
     * 
     * @param string $transactionId
     * @return array Payment verification data
     */
    public function verifyPayment(string $transactionId): array
    {
        try {
            $authToken = $this->authenticate();

            $response = Http::get(config('paymob.urls.base') . '/acceptance/transactions/' . $transactionId, [
                'token' => $authToken,
            ]);

            if ($response->successful()) {
                return $response->json();
            }

            throw new \Exception('Paymob payment verification failed: ' . $response->body());
        } catch (\Exception $e) {
            Log::error('Paymob payment verification error: ' . $e->getMessage());
            throw $e;
        }
    }
}
