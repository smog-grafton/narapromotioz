<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Third Party Services
    |--------------------------------------------------------------------------
    |
    | This file is for storing the credentials for third party services such
    | as Mailgun, Postmark, AWS and more. This file provides the de facto
    | location for this type of information, allowing packages to have
    | a conventional file to locate the various service credentials.
    |
    */

    'mailgun' => [
        'domain' => env('MAILGUN_DOMAIN'),
        'secret' => env('MAILGUN_SECRET'),
        'endpoint' => env('MAILGUN_ENDPOINT', 'api.mailgun.net'),
        'scheme' => 'https',
    ],

    'postmark' => [
        'token' => env('POSTMARK_TOKEN'),
    ],

    'ses' => [
        'key' => env('AWS_ACCESS_KEY_ID'),
        'secret' => env('AWS_SECRET_ACCESS_KEY'),
        'region' => env('AWS_DEFAULT_REGION', 'us-east-1'),
    ],

    'stripe' => [
        'public' => env('STRIPE_PUBLIC_KEY'),
        'secret' => env('STRIPE_SECRET_KEY'),
        'webhook_secret' => env('STRIPE_WEBHOOK_SECRET'),
    ],

    'paypal' => [
        'client_id' => env('PAYPAL_CLIENT_ID'),
        'client_secret' => env('PAYPAL_CLIENT_SECRET'),
        'environment' => env('PAYPAL_ENVIRONMENT', 'sandbox'), // sandbox or production
        'return_url' => env('PAYPAL_RETURN_URL', config('app.url') . '/payments/paypal/callback'),
        'cancel_url' => env('PAYPAL_CANCEL_URL', config('app.url') . '/payments/paypal/cancelled'),
        'webhook_id' => env('PAYPAL_WEBHOOK_ID'),
    ],

    'pesapal' => [
        'consumer_key' => env('PESAPAL_CONSUMER_KEY'),
        'consumer_secret' => env('PESAPAL_CONSUMER_SECRET'),
        'base_url' => env('PESAPAL_BASE_URL', 'https://pay.pesapal.com/v3'),
        'callback_url' => env('PESAPAL_CALLBACK_URL', config('app.url') . '/payments/pesapal/callback'),
        'ipn_url' => env('PESAPAL_IPN_URL', config('app.url') . '/api/webhooks/pesapal'),
    ],

    'airtel' => [
        'client_id' => env('AIRTEL_CLIENT_ID'),
        'client_secret' => env('AIRTEL_CLIENT_SECRET'),
        'base_url' => env('AIRTEL_BASE_URL', 'https://openapi.airtel.africa'),
        'callback_url' => env('AIRTEL_CALLBACK_URL', config('app.url') . '/api/webhooks/airtel'),
        'merchant_code' => env('AIRTEL_MERCHANT_CODE'),
        'country_code' => env('AIRTEL_COUNTRY_CODE', 'KE'),
        'currency' => env('AIRTEL_CURRENCY', 'USD'),
        'environment' => env('AIRTEL_ENVIRONMENT', 'sandbox'),
    ],

    'mtn' => [
        'api_key' => env('MTN_API_KEY'),
        'api_user' => env('MTN_API_USER'),
        'api_password' => env('MTN_API_PASSWORD'),
        'base_url' => env('MTN_BASE_URL', 'https://sandbox.momodeveloper.mtn.com'),
        'callback_url' => env('MTN_CALLBACK_URL', config('app.url') . '/api/webhooks/mtn'),
        'country_code' => env('MTN_COUNTRY_CODE', 'UG'),
        'currency' => env('MTN_CURRENCY', 'USD'),
        'environment' => env('MTN_ENVIRONMENT', 'sandbox'),
        'subscription_key' => env('MTN_SUBSCRIPTION_KEY'),
    ],

    // Video Streaming Service Integration
    'wowza' => [
        'api_key' => env('WOWZA_API_KEY'),
        'access_key' => env('WOWZA_ACCESS_KEY'),
        'base_url' => env('WOWZA_BASE_URL'),
        'application_name' => env('WOWZA_APPLICATION_NAME', 'live'),
        'stream_target' => env('WOWZA_STREAM_TARGET'),
    ],

    'mux' => [
        'token_id' => env('MUX_TOKEN_ID'),
        'token_secret' => env('MUX_TOKEN_SECRET'),
        'webhook_secret' => env('MUX_WEBHOOK_SECRET'),
    ],

    'cloudflare' => [
        'account_id' => env('CLOUDFLARE_ACCOUNT_ID'),
        'api_key' => env('CLOUDFLARE_API_KEY'),
        'api_email' => env('CLOUDFLARE_API_EMAIL'),
        'stream_token' => env('CLOUDFLARE_STREAM_TOKEN'),
    ],

    'agora' => [
        'app_id' => env('AGORA_APP_ID'),
        'app_certificate' => env('AGORA_APP_CERTIFICATE'),
        'customer_key' => env('AGORA_CUSTOMER_KEY'),
        'customer_secret' => env('AGORA_CUSTOMER_SECRET'),
    ],
];