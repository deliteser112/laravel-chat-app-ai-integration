<?php

namespace App\Models;

use App\Enums\ChatbotProvider;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ChatbotSetting extends Model
{
    use HasFactory;

    protected $fillable = ['provider'];

    public static function current(): self
    {
        return static::query()->first()
            ?? static::create(['provider' => ChatbotProvider::OPENAI->value]);
    }
}
