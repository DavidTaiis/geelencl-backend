<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Third Party Services
    |--------------------------------------------------------------------------
    |
    | This file is for storing the credentials for third party services such
    | as Stripe, Mailgun, SparkPost and others. This file provides a sane
    | default location for this type of information, allowing packages
    | to have a conventional place to find your various credentials.
    |
    */

    'mailgun' => [
        'domain' => env('MAILGUN_DOMAIN'),
        'secret' => env('MAILGUN_SECRET'),
    ],

    'ses' => [
        'key' => env('SES_KEY'),
        'secret' => env('SES_SECRET'),
        'region' => 'us-east-1',
    ],

    'sparkpost' => [
        'secret' => env('SPARKPOST_SECRET'),
    ],

    'stripe' => [
        'model' => App\User::class,
        'key' => env('STRIPE_KEY'),
        'secret' => env('STRIPE_SECRET'),
    ],
    'ms_shopping_cart' => [
        'url' => env('MS_SHOPPING_CART_HOST')
    ],
    'place_to_pay' => [
        'host' => env('PLACE_TO_PAY_HOST'),
        'login' => env('PLACE_TO_PAY_LOGIN'),
        'secret_key' => env('PLACE_TO_PAY_SECRET_KEY'),
        'return_url' => env('URL_FRONTEND')
    ],
    'strava' => [
        'host' => env('STRAVA_HOST'),
        'radar_app_id' => env('RADAR_APP_ID')
    ],
    'sendgrid' => [
        'user_template_id' => env('SENDGRID_TEMPLATE_ID'),
        'api_key' => env('SENDGRID_API_KEY'),
        'from_email' => env('SENGRID_EMAIL'),
    ],
    'aws' => [
        'url' => env('AWS_URL'),
    ],
];
