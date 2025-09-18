<?php

namespace App\Contracts;

use Generator;

interface BotProvider
{
    public function reply(string $prompt, array $options = []): string;

    public function name(): string;
}
