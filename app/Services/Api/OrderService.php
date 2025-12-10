<?php

namespace App\Services\Api;

use App\Models\Order;

class OrderService
{
    /**
     * Create a new order
     * 
     * @param array $data Order data (amount, currency, customer_email)
     * @return Order
     */
    public function createOrder(array $data): Order
    {
        return Order::create([
            'amount' => $data['amount'],
            'currency' => $data['currency'],
            'customer_email' => $data['customer_email'],
            'status' => 'pending',
        ]);
    }
}
