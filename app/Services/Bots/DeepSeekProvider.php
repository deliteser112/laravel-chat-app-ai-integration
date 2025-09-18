<?php

namespace App\Services\Bots;

use App\Contracts\BotProvider;
use GuzzleHttp\Client;

class DeepSeekProvider implements BotProvider
{
    public function __construct(private Client $http, private string $key, private string $model) {}

    public function name(): string { return 'deepseek:'.$this->model; }

    public function reply(string $prompt, array $options = []): string
    {
        $r = $this->http->post('https://api.deepseek.com/chat/completions', [
            'headers' => ['Authorization' => "Bearer {$this->key}"],
            'json' => [
                'model' => $options['model'] ?? $this->model,
                'messages' => [
                    [
                        'role' => 'system',
                        'content' => $options['system'] ?? 'You are a helpful assistant.'
                    ],
                    [
                        'role' => 'user',
                        'content' => $prompt
                    ],
                ],
                'temperature' => $options['temperature'] ?? 0.2,
                'stream' => false,
            ],
            'timeout' => 30,
        ]);
        $data = json_decode($r->getBody()->getContents(), true);
        return $data['choices'][0]['message']['content'] ?? '';
    }
}
