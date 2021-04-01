<?php

namespace App\Http\Controllers\Room;

use App\Models\User;
use App\Models\Room;
use App\Http\Controllers\Room\RoomRepository;

class RoomService
{
    public function listRoomsByUser(User $user)
    {
        $where = [
            ['user_id', '=', $user->id]
        ];
        $rooms = app(RoomRepository::class)->listRoomsByUser($where);
        return $this->composeRoomData($rooms, $user);
    }

    public function composeRoomData($rooms, $user)
    {
        $results = [];
        foreach ($rooms as $key => $room) {
            $results[$key]["room_id"] = $room->room_id;
            $users = $this->findUsersByRoomId($room->room_id);
            $results[$key]["ids"] = $users->pluck('user')->pluck('id')->filter(function($value) use ($user){
                return $value != $user->id;
            })->values()->all();
            if ($room->name) {
                $results[$key]["name"] = $room->name;
                continue; 
            }
            $results[$key]["name"] = $this->setRoomName($users->toArray(), $user->id);
        }
        return $results;
    }

    public function setRoomName($users, $currentUser)
    {
        $users = array_filter($users, function ($user) use ($currentUser) {
            return $user["user"]["id"] != $currentUser;
        });
        if (count($users) == 1) {
            return array_values($users)[0]["user"]["name"];
        }
        $names = array_column($users, 'name');
        return implode(' ,', $names);
    }

    public function authorizeUser(User $user, int $roomId)
    {
    	$where = [
            ['user_id', '=', $user->id],
            ['room_id', '=', $roomId]
        ];
        return app(RoomRepository::class)->listRoomsByUser($where)->count();
    }

    public function createRoom($userCreated)
    {
        $room = new Room();
        $room->user_created = $userCreated;
        return app(RoomRepository::class)->save($room);
    }

    public function joinUserRoom(Room $room, array $users)
    {
        $users = array_map(function($user) use ($room){
            return [
                'room_id' => $room->id,
                'user_id' => $user
            ];
        }, $users);
        return app(RoomRepository::class)->insertUserRoom($users);
    }

    public function findUsersByRoomId(int $roomId)
    {
        $where = [
            ['room_id', '=', $roomId]
        ];
        return app(RoomRepository::class)->listUsersByConditions($where);
    }
}
