<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\UserRoom;
use Illuminate\Notifications\Notifiable;
use Illuminate\Broadcasting\PrivateChannel;

class Room extends Model
{
    use Notifiable;

    protected $fillable = ['name', 'description', 'user_created'];

    public function user() {
        return $this->belongsTo(User::class, 'user_created');
    }

    public function users() {
    	return $this->hasMany(UserRoom::class, 'room_id');
    }

    /**
     * The channels the user receives notification broadcasts on.
     *
     * @return string
     */
    public function receivesBroadcastNotificationsOn()
    {
        return 'notify_room.'.$this->id;
    }
}
