<?php

namespace App\Listeners;

use App\Events\MessageSent;
use App\Models\Message;
use App\Services\BotManager;
use Illuminate\Contracts\Queue\ShouldQueue;

class ReplyAsBot implements ShouldQueue
{
    public function __construct(private readonly BotManager $bots) {}

    public function handle(MessageSent $event): void
    {
        $msg = $event->message;
        if ($event->isBot) return;

        $bot = $this->bots->resolve();
        $reply = trim($bot->reply($event->message, [
            'system' => 'You are a helpful assistant for a person who wants guide',
        ]));

        $botMsg = Message::query()->create([
            'user_id' => $event->user->id,
            'content'    => $reply ?: '...',
            'is_bot'  => true,
        ]);

        broadcast(new MessageSent($botMsg->user, $event->room, $botMsg->content, true));
    }
}
