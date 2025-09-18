<?php

namespace App\Services\Bots;

use App\Contracts\BotProvider;
use GuzzleHttp\Client;

class GeminiProvider implements BotProvider
{
    public function __construct(private Client $http, private string $key, private string $model) {}

    public function name(): string { return 'gemini:'.$this->model; }

    public function reply(string $prompt, array $options = []): string
    {
        $r = $this->http->post("https://generativelanguage.googleapis.com/v1beta/models/{$this->model}:generateContent", [
            'query' => ['key' => $this->key],
            'json'  => ['contents' => [[ 'parts' => [['text' => $prompt]] ]]],
            'timeout' => 30,
        ]);
        $data = json_decode($r->getBody()->getContents(), true);
        return $data['candidates'][0]['content']['parts'][0]['text'] ?? '';
    }
}
