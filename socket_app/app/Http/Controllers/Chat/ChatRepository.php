<?php

namespace App\Http\Controllers\Chat;

use App\Models\Chat;

class ChatRepository
{
    public function getChatByConditions(array $where, array $select = ['*'])
    {
        return Chat::with('sender')->select($select)->where($where)->get();
    }

    public function save(Chat $chat)
    {
    	return $chat->save();
    }
}
