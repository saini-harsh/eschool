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

    'rapidapi' => [
        'key' => env('RAPIDAPI_KEY'),
        'TEXTFLOW_API_KEY' => env('TEXTFLOW_API_KEY'),
        'email_host' => env('RAPIDAPI_EMAIL_HOST', 'rapid-send-email.p.rapidapi.com'),
        'email_url' => env('RAPIDAPI_EMAIL_URL', 'https://rapid-send-email.p.rapidapi.com/send'),
        'sms_host' => env('RAPIDAPI_SMS_HOST', 'rapid-sms.p.rapidapi.com'),
        'sms_url' => env('RAPIDAPI_SMS_URL', 'https://rapid-sms.p.rapidapi.com/send')
    ],

    'sms' => [
        'from' => env('SMS_FROM_NUMBER', 'EnvisionTechSolution'),
    ],
    'whatsapp' => [
        'from' => env('WHATSAPP_FROM_NUMBER', 'EnvisionTechSolution'),
    ],

];
