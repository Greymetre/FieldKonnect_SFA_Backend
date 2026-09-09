<?php

return [

    'plivo' => [
        'auth_id' => env('PLIVO_AUTH_ID'),
        'auth_token' => env('PLIVO_AUTH_TOKEN'),
        'from_number' => env('PLIVO_FROM_NUMBER'),
        'answer_url' => env('PLIVO_ANSWER_URL'),
        'status_url' => env('PLIVO_STATUS_URL'),
        'recording_url' => env('PLIVO_RECORDING_URL'),
        'browser_app_id' => env('PLIVO_BROWSER_APP_ID'),
        // Plivo's INR console uses its own billing-credit conversion, not the live forex rate.
        'usd_to_inr_rate' => env('PLIVO_USD_TO_INR_RATE', 80.00),
    ],

    // Dedicated inbound/outbound number for the isolated Client Calling flow.
    // Do not fall back to the legacy Plivo number: a missing value must fail
    // closed so an old caller ID is never used accidentally.
    'client_calling' => [
        'enabled' => env('CLIENT_CALLING_ENABLED', false),
        'auth_id' => env('CLIENT_CALLING_PLIVO_AUTH_ID', env('PLIVO_AUTH_ID')),
        'auth_token' => env('CLIENT_CALLING_PLIVO_AUTH_TOKEN', env('PLIVO_AUTH_TOKEN')),
        'number' => env('CLIENT_CALLING_PLIVO_NUMBER'),
        'app_id' => env('CLIENT_CALLING_PLIVO_APP_ID'),
        'inbound_url' => env('CLIENT_CALLING_INBOUND_URL'),
        'outbound_answer_url' => env('CLIENT_CALLING_OUTBOUND_ANSWER_URL'),
        'status_url' => env('CLIENT_CALLING_STATUS_URL'),
        'recording_url' => env('CLIENT_CALLING_RECORDING_URL'),
        'fallback_url' => env('CLIENT_CALLING_FALLBACK_URL'),
        'webhook_secret' => env('CLIENT_CALLING_WEBHOOK_SECRET'),
        'ring_timeout' => (int) env('CLIENT_CALLING_RING_TIMEOUT', 30),
        'recording_enabled' => env('CLIENT_CALLING_RECORDING_ENABLED', true),
        'validate_signature' => env('CLIENT_CALLING_VALIDATE_SIGNATURE', true),
    ],

    'sarvam' => [
        'api_key' => env('SARVAM_API_KEY'),
        'model' => env('SARVAM_STT_MODEL', 'saaras:v3'),
        'mode' => env('SARVAM_STT_MODE', 'codemix'),
        'language_code' => env('SARVAM_STT_LANGUAGE', 'unknown'),
    ],

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

    'exotel' => [
        'sid' => env('EXOTEL_SID'),
        'api_key' => env('EXOTEL_API_KEY'),
        'token' => env('EXOTEL_TOKEN'),
        'virtual_number' => env('EXOTEL_VIRTUAL_NUMBER'),
    ],

    'google' => [
        'maps_api_key' => 'AIzaSyAVSDwHbKULnZa93kYpYINTqX4eaWy9q18',
    ],

];
