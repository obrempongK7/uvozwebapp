<?php
return [
    'stripe' => ['key' => env('STRIPE_KEY'), 'secret' => env('STRIPE_SECRET'), 'webhook_secret' => env('STRIPE_WEBHOOK_SECRET')],
    'paystack' => ['public_key' => env('PAYSTACK_PUBLIC_KEY'), 'secret_key' => env('PAYSTACK_SECRET_KEY'), 'payment_url' => env('PAYSTACK_PAYMENT_URL', 'https://api.paystack.co')],
    'flutterwave' => ['public_key' => env('FLUTTERWAVE_PUBLIC_KEY'), 'secret_key' => env('FLUTTERWAVE_SECRET_KEY'), 'encryption_key' => env('FLUTTERWAVE_ENCRYPTION_KEY')],
    'paypal' => ['client_id' => env('PAYPAL_CLIENT_ID'), 'client_secret' => env('PAYPAL_CLIENT_SECRET'), 'mode' => env('PAYPAL_MODE', 'sandbox')],
    'pusher' => ['app_id' => env('PUSHER_APP_ID'), 'app_key' => env('PUSHER_APP_KEY'), 'app_secret' => env('PUSHER_APP_SECRET'), 'host' => env('PUSHER_HOST') ?: 'api-'.env('PUSHER_APP_CLUSTER', 'mt1').'.pusher.com', 'port' => env('PUSHER_PORT', 443), 'scheme' => env('PUSHER_SCHEME', 'https'), 'app_cluster' => env('PUSHER_APP_CLUSTER', 'mt1')],
];
