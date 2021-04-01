<?php

/*
|--------------------------------------------------------------------------
| Broadcast Channels
|--------------------------------------------------------------------------
|
| Here you may register all of the event broadcasting channels that your
| application supports. The given channel authorization callbacks are
| used to check if an authenticated user can listen to the channel.
|
*/

// Broadcast::channel('App.User.{id}', function ($user, $id) {
//     return (int) $user->id === (int) $id;
// });

Broadcast::channel('room.{id}', function ($user, $id) {
	if (app('App\Http\Controllers\Room\RoomService')->authorizeUser($user, $id)) {
		return $user;
	}
    return false;
});

Broadcast::channel('messages', function ($user) {
	return $user;
});

Broadcast::channel('notify_users.{id}', function ($user, $id) {
	return true;
});

Broadcast::channel('notify_room.{id}', function ($user, $id) {
	return true;
});
