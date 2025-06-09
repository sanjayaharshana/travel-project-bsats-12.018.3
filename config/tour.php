<?php

return [
    /*
    |--------------------------------------------------------------------------
    | Tour Generation Settings
    |--------------------------------------------------------------------------
    |
    | Configuration for tour route generation including timeouts and limits
    |
    */

    'timeouts' => [
        'ajax_request' => 300, // 5 minutes for AJAX requests
        'ai_processing' => 600, // 10 minutes for AI processing
        'warning_threshold' => 180, // Show warning after 3 minutes
        'curl_timeout' => 600, // 10 minutes for cURL requests
        'curl_connect_timeout' => 30, // 30 seconds for connection
    ],

    'ai' => [
        'model' => env('OPENAI_MODEL', 'gpt-4'),
        'temperature' => 0.7,
        'max_tokens' => 2000,
        'retry_attempts' => 3,
        'retry_delay' => 5, // seconds
        'timeout' => 600, // 10 minutes timeout for OpenAI API calls
    ],

    'validation' => [
        'max_locations_per_route' => 10,
        'min_locations_per_route' => 2,
        'max_routes_per_request' => 5,
    ],

    'caching' => [
        'enabled' => env('TOUR_CACHE_ENABLED', true),
        'ttl' => 3600, // 1 hour cache
        'prefix' => 'tour_routes_',
    ],

    'rate_limiting' => [
        'enabled' => env('TOUR_RATE_LIMIT_ENABLED', true),
        'max_requests_per_minute' => 10,
        'max_requests_per_hour' => 100,
    ],

    'notifications' => [
        'email_on_completion' => env('TOUR_EMAIL_NOTIFICATIONS', false),
        'webhook_url' => env('TOUR_WEBHOOK_URL', null),
    ],

    'logging' => [
        'enabled' => env('TOUR_LOGGING_ENABLED', true),
        'level' => env('TOUR_LOG_LEVEL', 'info'),
        'include_request_data' => env('TOUR_LOG_REQUEST_DATA', false),
    ],
]; 