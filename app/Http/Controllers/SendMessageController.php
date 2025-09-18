<?php

namespace App\Http\Controllers;

use App\Events\MessageSent;
use App\Models\Message;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Response;

class SendMessageController extends Controller
{
    /**
     * Send the message to a room.
     *
     * @param  Request  $request
     * @return JsonResponse
     */
    public function __invoke(Request $request)
    {
        $msg = Message::query()->create([
            'user_id' => $request->user()->id,
            'content' => $request->input('message'),
            'is_bot'  => false,
        ]);

        MessageSent::broadcast(
            $request->user(),
            $request->input('room'),
            $request->input('message'),
            false
        );

        return Response::json(['ok' => true]);
    }
}
