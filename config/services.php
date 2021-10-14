<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Third Party Services
    |--------------------------------------------------------------------------
    |
    | This file is for storing the credentials for third party services such
    | as Mailgun, SparkPost and others. This file provides a sane default
    | location for this type of information, allowing packages to have
    | a conventional file to locate the various service credentials.
    |
    */

    'mailgun' => [
        'domain' => env('MAILGUN_DOMAIN'),
        'secret' => env('MAILGUN_SECRET'),
        'endpoint' => env('MAILGUN_ENDPOINT', 'api.mailgun.net'),
    ],

    'postmark' => [
        'token' => env('POSTMARK_TOKEN'),
    ],

    'ses' => [
        'key' => env('AWS_ACCESS_KEY_ID'),
        'secret' => env('AWS_SECRET_ACCESS_KEY'),
        'region' => env('AWS_DEFAULT_REGION', 'us-east-1'),
    ],

    'sparkpost' => [
        'secret' => env('SPARKPOST_SECRET'),
    ],

    'discord' => [
        'client_id' => env('DISCORD_CLIENT_ID'),
        'client_secret' => env('DISCORD_CLIENT_SECRET'),
        'redirect' => env('DISCORD_REDIRECT', '/login/callback'),
        'api_base_url' => env('DISCORD_API_BASE_URL', 'https://discordapp.com/api/'),
        'token' => env('DISCORD_BOT_TOKEN', 'your-bot-token'),
        'webhook-id' => env('DISCORD_WEBHOOK_ID', 'your-webhook-id'),
        'webhook-token' => env('DISCORD_WEBHOOK_TOKEN', 'your-webhook-token'),
    ],

    'cloudflare' => [
        'base-uri' => env('CLOUDFLARE_API_BASE_URL', 'https://api.cloudflare.com/client/v4/'),
        'server-ip' => env('CLOUDFLARE_SERVER_IP', '127.0.0.1'),
        'zone-id' => env('CLOUDFLARE_ZONE_IDENTIFIER', 'your-zone-id'),
        'api-token' => env('CLOUDFLARE_API_TOKEN', 'your-api-key'),
        'email' => env('CLOUDFLARE_EMAIL', 'you@yourdomain.com')
    ],
];
