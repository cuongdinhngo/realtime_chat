<?php

namespace App\Http\Controllers\User;


use App\Http\Controllers\Controller as BaseController;
use Illuminate\Http\Request;
use App\Http\Controllers\User\UserService;
use App\Http\Controllers\Room\RoomService;
use Illuminate\Support\Facades\Auth;

class UserController extends BaseController
{
    public $userService;

    public function __construct(UserService $userService)
    {
        $this->userService = $userService;
    }

    /**
     * Get current user login
     *
     * @return [type] [description]
     */
    public function getCurrentUserLogin()
    {
        return Auth::user();
    }

    /**
     * Get user
     *
     * @param  Request $request [description]
     * @return [type]           [description]
     */
    public function getUser(Request $request)
    {
        $user = $this->userService->findUserById($request->id);
        $room = $this->userService->matchUsersToRoom(Auth::user()->id, $request->id)->toArray();
        return view("user.show", compact(['user', 'room']));
    }

    /**
     * User join room
     *
     * @param  Request     $request     [description]
     * @param  RoomService $roomService [description]
     * @return [type]                   [description]
     */
    public function connect(Request $request, RoomService $roomService)
    {
        try {
            \DB::beginTransaction();
            $user = Auth::user();
            $room = $roomService->createRoom($user->id);
            $roomService->joinUserRoom($room, [$user->id, $request->id]);
            \DB::commit();
            return redirect()->route("rooms.enter", ["room_id" => $room->id]);
        } catch (\Exception $e) {
            \DB::rollback();
            report($e);
        }
    }

    /**
     * List notifications
     *
     * @param  Request $request [description]
     * @return [type]           [description]
     */
    public function listNotifications(Request $request)
    {
        $userNotifications = Auth::user()->notifications;
        return view("notification", compact("userNotifications"));
    }
}
