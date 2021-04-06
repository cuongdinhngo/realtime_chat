<?php

namespace App\Http\Controllers\Room;

use App\Models\UserRoom;
use App\Models\User;
use App\Models\Room;

class RoomRepository
{
    /**
     * List rooms by user
     *
     * @param  array  $where  [description]
     * @param  array  $select [description]
     * @return [type]         [description]
     */
    public function listRoomsByUser(array $where, array $select = ['*'])
    {
        return UserRoom::with('room')->select($select)->where($where)->get();
    }

    /**
     * List rooms
     *
     * @param  [type] $where [description]
     * @return [type]        [description]
     */
    public function listRooms($where)
    {
    	return UserRoom::join('rooms', 'user_room.room_id', '=', 'rooms.id')
    					->join('users', 'user_room.user_id', '=', 'users.id')
    					->where($where)
    					->get();
    }

    /**
     * Store room data
     *
     * @param  Room   $room [description]
     * @return [type]       [description]
     */
    public function save(Room $room)
    {
        $room->save();
        return $room;
    }

    /**
     * Store user joined room
     *
     * @param  array  $data [description]
     * @return [type]       [description]
     */
    public function insertUserRoom(array $data)
    {
        return UserRoom::insert($data);
    }

    /**
     * List user by conditions
     *
     * @param  array  $where  [description]
     * @param  array  $select [description]
     * @return [type]         [description]
     */
    public function listUsersByConditions(array $where, array $select = ['*'])
    {
        return UserRoom::with('user')->select($select)->where($where)->get();
    }
}
