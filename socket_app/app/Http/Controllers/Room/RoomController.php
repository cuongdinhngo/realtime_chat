<?php

namespace App\Http\Controllers\Room;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Auth;
use App\Http\Controllers\Room\RoomService;
use App\Http\Controllers\Chat\ChatService;
use App\Events\PrivateMessage;

class RoomController extends Controller
{
	public $roomService;
	public $chatService;

	public function __construct(RoomService $roomService, ChatService $chatService)
	{
		$this->roomService = $roomService;
		$this->chatService = $chatService;
	}

    /**
     * List all rooms belong to user
     *
     * @return mixed
     */
    public function listUserRooms()
    {
    	$user = Auth::user();
    	$rooms = $this->roomService->listRoomsByUser($user);
    	return $rooms;
    }

    /**
     * Enter the room
     *
     * @param  Request $request [description]
     * @return [type]           [description]
     */
    public function enterRoom(Request $request)
    {
        $this->roomService->updateNotificationByRoomId(Auth::user(), ["room_id" => $request->room_id]);
    	$messages = $this->chatService->listMessagesByConditions($request->room_id);
    	return view('room', ['id' => $request->id, 'messages' => $messages]);
    }

    /**
     * Chat in private
     *
     * @param  Request $request [description]
     * @return [type]           [description]
     */
    public function chat(Request $request)
    {
        try {
            $chat = $this->chatService->sendMessage($request->message, $request->room_id);
            broadcast(new PrivateMessage($chat->load('sender')))->toOthers();
            $users = $this->roomService->findUsersByRoomId($request->room_id);
            $currentUser = Auth::user();
            $users = $users->filter(function($value, $key) use ($currentUser) {
                return $value['user_id'] != $currentUser->id;
            });
            $this->roomService->sendNotifications($users, $request->room_id);
            return ['chat' => $chat->load('sender')];
        } catch (\Exception $e) {
            report($e);
        }
    }

    /**
     * Get users by room_id
     *
     * @param  Request $request [description]
     * @return [type]           [description]
     */
    public function getUsersByRoomId(Request $request)
    {
        return $this->roomService->findUsersByRoomId($request->room_id);
    }
}
