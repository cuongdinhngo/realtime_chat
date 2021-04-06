<?php

namespace App\Http\Controllers\Message;

use App\Models\Message;

class MessageRepository
{
    /**
     * Get Message with conditions
     *
     * @param  array  $where  [description]
     * @param  array  $select [description]
     *
     * @return Message
     */
    public function get(array $where = [], array $select = ['*'])
    {
        return Message::with('sender')->select($select)->where($where)->get();
    }

    /**
     * Store message
     *
     * @param  Message $message [description]
     * @return [type]           [description]
     */
    public function insert(Message $message)
    {
        return $message->save();
    }
}
