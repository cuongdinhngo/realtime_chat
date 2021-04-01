<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\User;
use App\Models\Room;

class Chat extends Model
{
    protected $fillable = ['room_id', 'sender_id', 'content'];

    public function sender() {
        return $this->belongsTo(User::class, 'sender_id');
    }

    public function room() {
        return $this->belongsTo(Room::class);
    }
}
