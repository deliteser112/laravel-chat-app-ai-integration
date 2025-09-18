<?php

return [
    'default' => env('CHATBOT_DEFAULT', 'openai'),
    'providers' => [
        'openai' => [
            'key' => env('OPENAI_API_KEY'),
            'model' => env('OPENAI_MODEL','gpt-4o-mini')
        ],
        'gemini' => [
            'key' => env('GEMINI_API_KEY'),
            'model' => env('GEMINI_MODEL','gemini-1.5-pro')
        ],
        'deepseek' => [
            'key' => env('DEEPSEEK_API_KEY'),
            'model' => env('DEEPSEEK_MODEL','deepseek-chat')
        ],
    ],
];
