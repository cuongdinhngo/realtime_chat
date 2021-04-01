<?php

namespace App\Http\Controllers\User;

use App\Models\User;
use App\Models\UserRoom;

class UserService
{
    public function findUserById(int $id)
    {
    	return User::find($id);
    }

    public function matchUsersToRoom($currentUser, $userId)
    {
        return UserRoom::join('user_room AS ur2', 'user_room.room_id', '=', 'ur2.room_id')
                        ->join('rooms AS r', 'user_room.room_id', '=', 'r.id')
                        ->where('r.is_group', 0)
                        ->where('user_room.user_id', $currentUser)
                        ->where('ur2.user_id', $userId)
                        ->select(['user_room.room_id as room_id', 'ur2.room_id as confirm_room'])
                        ->first();
    }
}
