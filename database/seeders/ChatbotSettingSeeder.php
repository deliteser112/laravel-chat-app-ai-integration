<?php

namespace Database\Seeders;

use App\Enums\ChatbotProvider;
use App\Models\ChatbotSetting;
use Illuminate\Database\Seeder;

class ChatbotSettingSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        ChatbotSetting::firstOrCreate([], ['provider' => ChatbotProvider::OPENAI->value]);
    }
}
