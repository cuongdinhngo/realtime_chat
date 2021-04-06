<?php

use App\Models\User;

function getNotifyMessage(array $data)
{
    $msg = config('messages.'.$data['code']);
    $user = User::find($data['sender_id']);
    return sprintf($msg, $user->name);
}