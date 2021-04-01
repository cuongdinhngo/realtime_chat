<?php

namespace App\Http\Controllers\Room;

use App\Models\UserRoom;
use App\Models\User;
use App\Models\Room;

class RoomRepository
{
    public function listRoomsByUser(array $where, array $select = ['*'])
    {
        return UserRoom::with('room')->select($select)->where($where)->get();
    }

    public function listRooms($where)
    {
    	return UserRoom::join('rooms', 'user_room.room_id', '=', 'rooms.id')
    					->join('users', 'user_room.user_id', '=', 'users.id')
    					->where($where)
    					->get();
    }

    public function save(Room $room)
    {
        $room->save();
        return $room;
    }

    public function insertUserRoom(array $data)
    {
        return UserRoom::insert($data);
    }

    public function listUsersByConditions(array $where, array $select = ['*'])
    {
        return UserRoom::with('user')->select($select)->where($where)->get();
    }
}
