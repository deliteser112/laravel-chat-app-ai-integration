<?php

namespace App\Services;

use App\Contracts\BotProvider;
use App\Models\ChatbotSetting;
use App\Services\Bots\{OpenAIProvider, GeminiProvider, DeepSeekProvider};
use GuzzleHttp\Client;

class BotManager
{
    public function resolve(): BotProvider
    {
        $current = ChatbotSetting::current()->provider;

        return match ($current) {
            'gemini'   => new GeminiProvider(new Client(), config('chatbot.providers.gemini.key'),   config('chatbot.providers.gemini.model')),
            'deepseek' => new DeepSeekProvider(new Client(), config('chatbot.providers.deepseek.key'), config('chatbot.providers.deepseek.model')),
            default    => new OpenAIProvider(new Client(), config('chatbot.providers.openai.key'),   config('chatbot.providers.openai.model')),
        };
    }
}
