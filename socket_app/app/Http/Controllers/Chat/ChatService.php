<?php

namespace App\Http\Controllers\Chat;

use App\Http\Controllers\Chat\ChatRepository;
use App\Models\Chat;
use Auth;

class ChatService
{
    public function listMessagesByConditions(int $roomId)
    {
        $where = [
            ['room_id', '=', $roomId]
        ];
        return app(ChatRepository::class)->getChatByConditions($where);
    }

    public function sendMessage(string $content, int $roomId)
    {
    	$chat = $this->prepareSendData($content, $roomId);
    	app(ChatRepository::class)->save($chat);
    	return $chat;
    }

    public function prepareSendData(string $content, int $roomId)
    {
    	$chat = new Chat();
    	$chat->content = $content;
    	$chat->room_id = $roomId;
    	$chat->sender_id = Auth::user()->id;
    	return $chat;
    }
}
