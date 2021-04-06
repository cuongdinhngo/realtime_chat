<?php

namespace App\Http\Controllers\Chat;

use App\Http\Controllers\Chat\ChatRepository;
use App\Models\Chat;
use Auth;

class ChatService
{
    /**
     * List chat message by conditions
     *
     * @param  int    $roomId [description]
     * @return [type]         [description]
     */
    public function listMessagesByConditions(int $roomId)
    {
        $where = [
            ['room_id', '=', $roomId]
        ];
        return app(ChatRepository::class)->getChatByConditions($where);
    }

    /**
     * send chat message
     *
     * @param  string $content [description]
     * @param  int    $roomId  [description]
     * @return [type]          [description]
     */
    public function sendMessage(string $content, int $roomId)
    {
    	$chat = $this->prepareSendData($content, $roomId);
    	app(ChatRepository::class)->save($chat);
    	return $chat;
    }

    /**
     * Prepare send data
     *
     * @param  string $content [description]
     * @param  int    $roomId  [description]
     * @return [type]          [description]
     */
    public function prepareSendData(string $content, int $roomId)
    {
    	$chat = new Chat();
    	$chat->content = $content;
    	$chat->room_id = $roomId;
    	$chat->sender_id = Auth::user()->id;
    	return $chat;
    }
}
