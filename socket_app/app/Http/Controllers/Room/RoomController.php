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

    public function listUserRooms()
    {
    	$user = Auth::user();
    	$rooms = $this->roomService->listRoomsByUser($user);
    	return $rooms;
    }

    public function enterRoom(Request $request)
    {
    	$messages = $this->chatService->listMessagesByConditions($request->room_id);
    	return view('room', ['id' => $request->id, 'messages' => $messages]);
    }

    public function chat(Request $request)
    {
        try {
            $chat = $this->chatService->sendMessage($request->message, $request->room_id);
            broadcast(new PrivateMessage($chat->load('sender')))->toOthers();
            return ['chat' => $chat->load('sender')];
        } catch (\Exception $e) {
            report($e);
        }
    }

    public function getUsersByRoomId(Request $request)
    {
        return $this->roomService->findUsersByRoomId($request->room_id);
    }
}
