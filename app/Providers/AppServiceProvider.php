<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        // Bind PaymentGatewayInterface to PaymobPaymentService
        $this->app->bind(
            \App\Contracts\PaymentGatewayInterface::class,
            \App\Services\Api\PaymobPaymentService::class
        );
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        //
    }
}
