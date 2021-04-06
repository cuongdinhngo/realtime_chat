<?php

namespace App\Http\Controllers\Chat;

use App\Models\Chat;

class ChatRepository
{
	/**
	 * get chat message by conditions
	 *
	 * @param  array  $where  [description]
	 * @param  array  $select [description]
	 * @return [type]         [description]
	 */
    public function getChatByConditions(array $where, array $select = ['*'])
    {
        return Chat::with('sender')->select($select)->where($where)->get();
    }

    /**
     * Store chat message
     *
     * @param  Chat   $chat [description]
     * @return [type]       [description]
     */
    public function save(Chat $chat)
    {
    	return $chat->save();
    }
}
