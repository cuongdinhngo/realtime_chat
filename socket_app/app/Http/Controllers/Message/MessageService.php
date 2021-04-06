<?php

namespace App\Http\Controllers\Message;

use App\Http\Controllers\Message\MessageRepository;
use App\Models\Message;
use App\Models\User;

class MessageService
{
    /**
     * Get message in public room
     *
     * @return [type] [description]
     */
    public function getMessagesInPublicRoom()
    {
        return app(MessageRepository::class)->get();
    }

    /**
     * Post message in Public room
     *
     * @param  User   $user [description]
     * @return [type]       [description]
     */
    public function postMessageInPublicRoom(User $user)
    {
        $message = $this->prepareInsertMessageData($user);
        app(MessageRepository::class)->insert($message);
        return $message;
    }

    /**
     * Prepare insert message data
     *
     * @param  User   $user [description]
     * @return [type]       [description]
     */
    public function prepareInsertMessageData(User $user)
    {
        $message = new Message();
        $message->content = request()->get('message', '');
        $message->user_id = $user->id;
        return $message;
    }
}
