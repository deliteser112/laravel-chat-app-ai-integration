<?php

namespace App\DTOs;

class BotMessage
{
    public string $role;
    public string $content;

    public function __construct(string $role, string $content)
    {
        $this->role = $role;
        $this->content = $content;
    }
}
