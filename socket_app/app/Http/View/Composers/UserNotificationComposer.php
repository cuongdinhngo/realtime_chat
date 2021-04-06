<?php

namespace App\Http\View\Composers;

use Illuminate\View\View;
use App\Http\Controllers\Room\RoomRepository;
use Auth;
use App\Models\Room;

class UserNotificationComposer
{
    protected $user;

    /**
     * Create a new profile composer.
     *
     * @param  UserRepository  $users
     * @return void
     */
    public function __construct()
    {
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
        $view->with('userNotifications', $this->user->unreadNotifications);
    }
}