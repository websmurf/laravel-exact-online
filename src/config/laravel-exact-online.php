<?php

return [

    /**
     * The Client ID of your Exact Online application.
     */
    'exact_client_id' => env('EXACT_CLIENT_ID', ''),

    /**
     * The Client Secret of your Exact Online application.
     */
    'exact_client_secret' => env('EXACT_CLIENT_SECRET', ''),

    /**
     * The Division of your Exact Online application.
     */
    'exact_division' => env('EXACT_DIVISION', ''),

    /**
     * The Client country code
     */
    'exact_country_code' => env('EXACT_COUNTRY_CODE', 'nl'),

    /**
     * The Client country code
     */
    'exact_multi_user' => env('EXACT_MULTI_USER', false),

    /**
     * The Webhook secret
     */
    'exact_webhook_secret' => env('EXACT_WEBHOOK_SECRET', ''),

    /**
     * The web url
     */
    'exact_web_url' => env('EXACT_WEB_URL', ''),

    /**
     * The storage
     */
    'exact-api-storage' => env('EXACT_API_STORAGE', 'local'),
];
