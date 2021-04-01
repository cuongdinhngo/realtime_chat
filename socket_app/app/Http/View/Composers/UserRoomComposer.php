<?php

namespace App\Http\View\Composers;

use Illuminate\View\View;
use App\Http\Controllers\Room\RoomRepository;
use Auth;
use App\Models\Room;

class UserRoomComposer
{
    protected $roomRepository;
    protected $user;

    /**
     * Create a new profile composer.
     *
     * @param  UserRepository  $users
     * @return void
     */
    public function __construct(RoomRepository $roomRepository)
    {
        // Dependencies automatically resolved by service container...
        $this->roomRepository = $roomRepository;
        $this->user = Auth::user();
    }

    /**
     * Bind data to the view.
     *
     * @param  View  $view
     * @return void
     */
    public function compose(View $view)
    {
        $view->with('data', [
            'rooms' => $this->getRooms(),
            'user' => $this->user
        ]);
    }

    public function getRooms()
    {
        $where = [
            ['user_id', '=', $this->user->id]
        ];
        $rooms = $this->roomRepository->listRoomsByUser($where);
        return $rooms;
    }
}