<?php

return [
    /*
    |--------------------------------------------------------------------------
    | OpenAI Configuration
    |--------------------------------------------------------------------------
    |
    | Configuration for OpenAI API integration
    |
    */

    'api_key' => env('OPENAI_API_KEY'),

    'organization' => env('OPENAI_ORGANIZATION'),

    'request_timeout' => env('OPENAI_REQUEST_TIMEOUT', 600), // 10 minutes

    'connect_timeout' => env('OPENAI_CONNECT_TIMEOUT', 30), // 30 seconds

    'max_retries' => env('OPENAI_MAX_RETRIES', 3),

    'retry_delay' => env('OPENAI_RETRY_DELAY', 5), // seconds

    'http_client' => [
        'timeout' => env('OPENAI_HTTP_TIMEOUT', 600),
        'connect_timeout' => env('OPENAI_CONNECT_TIMEOUT', 30),
        'verify' => env('OPENAI_VERIFY_SSL', true),
    ],

    'models' => [
        'default' => env('OPENAI_MODEL', 'gpt-4'),
        'chat' => env('OPENAI_CHAT_MODEL', 'gpt-4'),
        'completion' => env('OPENAI_COMPLETION_MODEL', 'text-davinci-003'),
    ],

    'defaults' => [
        'temperature' => 0.7,
        'max_tokens' => 2000,
        'top_p' => 1,
        'frequency_penalty' => 0,
        'presence_penalty' => 0,
    ],

    /*
    |--------------------------------------------------------------------------
    | OpenAI API Project
    |--------------------------------------------------------------------------
    |
    | Here you may specify your OpenAI API project. This is used optionally in
    | situations where you are using a legacy user API key and need association
    | with a project. This is not required for the newer API keys.
    */
    'project' => env('OPENAI_PROJECT'),

    /*
    |--------------------------------------------------------------------------
    | OpenAI Base URL
    |--------------------------------------------------------------------------
    |
    | Here you may specify your OpenAI API base URL used to make requests. This
    | is needed if using a custom API endpoint. Defaults to: api.openai.com/v1
    */
    'base_uri' => env('OPENAI_BASE_URL'),
];
