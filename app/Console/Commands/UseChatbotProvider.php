<?php

namespace App\Console\Commands;

use App\Models\ChatbotSetting;
use Illuminate\Console\Command;

class UseChatbotProvider extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'chatbot:use {provider : openai|gemini|deepseek}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Switch active chatbot provider';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $p = strtolower($this->argument('provider'));
        if (!in_array($p, ['openai','gemini','deepseek'])) {
            $this->error('Invalid provider.');
            return self::INVALID;
        }
        ChatbotSetting::current()->update(['provider' => $p]);
        $this->info("Chatbot provider set to: {$p}");
        return self::SUCCESS;
    }
}
