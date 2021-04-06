<?php

namespace App\Http\Controllers\Room;

use App\Models\User;
use App\Models\Room;
use App\Http\Controllers\Room\RoomRepository;
use App\Notifications\MessageWasPosted;

class RoomService
{
    /**
     * List rooms by user
     *
     * @param  User   $user [description]
     * @return [type]       [description]
     */
    public function listRoomsByUser(User $user)
    {
        $where = [
            ['user_id', '=', $user->id]
        ];
        $rooms = app(RoomRepository::class)->listRoomsByUser($where);
        return $this->composeRoomData($rooms, $user);
    }

    /**
     * Compose room data
     *
     * @param  [type] $rooms [description]
     * @param  [type] $user  [description]
     * @return [type]        [description]
     */
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

    /**
     * Set Room name
     *
     * @param [type] $users       [description]
     * @param [type] $currentUser [description]
     */
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

    /**
     * Authorize user belong to room
     *
     * @param  User   $user   [description]
     * @param  int    $roomId [description]
     * @return [type]         [description]
     */
    public function authorizeUser(User $user, int $roomId)
    {
    	$where = [
            ['user_id', '=', $user->id],
            ['room_id', '=', $roomId]
        ];
        return app(RoomRepository::class)->listRoomsByUser($where)->count();
    }

    /**
     * Create room data
     *
     * @param  [type] $userCreated [description]
     * @return [type]              [description]
     */
    public function createRoom($userCreated)
    {
        $room = new Room();
        $room->user_created = $userCreated;
        return app(RoomRepository::class)->save($room);
    }

    /**
     * Join user room
     *
     * @param  Room   $room  [description]
     * @param  array  $users [description]
     * @return [type]        [description]
     */
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

    /**
     * Find users by room_id
     *
     * @param  int    $roomId [description]
     * @return [type]         [description]
     */
    public function findUsersByRoomId(int $roomId)
    {
        $where = [
            ['room_id', '=', $roomId]
        ];
        return app(RoomRepository::class)->listUsersByConditions($where);
    }

    /**
     * Send notifications
     *
     * @param  [type] $users  [description]
     * @param  [type] $roomId [description]
     * @return [type]         [description]
     */
    public function sendNotifications($users, $roomId)
    {
        $users->each(function($item, $key) use($roomId) {
            $item->user->notify(new MessageWasPosted($item->user, $roomId));
        });
    }

    /**
     * Mark as read in notifications
     *
     * @param  [type] $user   [description]
     * @param  [type] $roomId [description]
     * @return [type]         [description]
     */
    public function updateNotificationByRoomId($user, $roomId)
    {
        $updatedData = ['read_at' => now()->toDateTimeString()];
        \DB::table("notifications")
            ->where('notifiable_id', $user->id)
            ->whereRaw("JSON_CONTAINS(data, '".json_encode($roomId)."')")
            ->update($updatedData);
    }
}
