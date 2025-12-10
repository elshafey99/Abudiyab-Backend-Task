<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    protected $fillable = [
        'amount',
        'currency',
        'customer_email',
        'status',
    ];

    protected $casts = [
        'amount' => 'decimal:2',
    ];

    /**
     * Get all payments for this order
     */
    public function payments()
    {
        return $this->hasMany(Payment::class);
    }
}
