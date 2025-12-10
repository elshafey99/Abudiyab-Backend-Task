<?php

return [
    /*
    |--------------------------------------------------------------------------
    | Paymob API Key
    |--------------------------------------------------------------------------
    |
    | Your Paymob API key for authentication.
    | Get this from: Paymob Dashboard → Settings → Account Info → API Keys
    |
    */
    'api_key' => env('PAYMOB_API_KEY'),

    /*
    |--------------------------------------------------------------------------
    | Integration ID
    |--------------------------------------------------------------------------
    |
    | Your Paymob integration ID for payment processing.
    | Get this from: Paymob Dashboard → Settings → Payment Integrations
    |
    */
    'integration_id' => env('PAYMOB_INTEGRATION_ID'),

    /*
    |--------------------------------------------------------------------------
    | Iframe ID
    |--------------------------------------------------------------------------
    |
    | Your Paymob iframe ID for displaying the payment page.
    | Get this from: Paymob Dashboard → Settings → Payment Integrations
    |
    */
    'iframe_id' => env('PAYMOB_IFRAME_ID'),

    /*
    |--------------------------------------------------------------------------
    | HMAC Secret
    |--------------------------------------------------------------------------
    |
    | Your Paymob HMAC secret for verifying webhook callbacks.
    | Get this from: Paymob Dashboard → Settings → Account Info
    |
    */
    'hmac_secret' => env('PAYMOB_HMAC_SECRET'),

    /*
    |--------------------------------------------------------------------------
    | API URLs
    |--------------------------------------------------------------------------
    |
    | Paymob API endpoints for different operations.
    |
    */
    'urls' => [
        'base' => 'https://accept.paymob.com/api',
        'auth' => 'https://accept.paymob.com/api/auth/tokens',
        'order' => 'https://accept.paymob.com/api/ecommerce/orders',
        'payment_key' => 'https://accept.paymob.com/api/acceptance/payment_keys',
        'iframe' => 'https://accept.paymob.com/api/acceptance/iframes',
    ],

    /*
    |--------------------------------------------------------------------------
    | Currency
    |--------------------------------------------------------------------------
    |
    | Default currency for payments.
    |
    */
    'currency' => env('PAYMOB_CURRENCY', 'EGP'),
];
