<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class PaymentResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'order' => [
                'id' => $this->order->id,
                'amount' => number_format($this->order->amount, 2, '.', ''),
                'currency' => $this->order->currency,
                'customer_email' => $this->order->customer_email,
            ],
            'payment_gateway' => $this->payment_gateway,
            'transaction_id' => $this->transaction_id,
            'amount' => number_format($this->amount, 2, '.', ''),
            'status' => $this->status,
            'payment_url' => $this->metadata['payment_url'] ?? null,
            'created_at' => $this->created_at?->format('Y-m-d H:i:s'),
        ];
    }
}
