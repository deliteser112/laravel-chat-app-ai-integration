<?php

namespace App\Enums;

enum ChatbotProvider: int
{
    case OPENAI = 1;
    case GEMINI = 2;
    case DEEP_SEEK = 3;
}
