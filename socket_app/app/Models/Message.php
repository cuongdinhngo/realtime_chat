<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\User;

class Message extends Model
{
    protected $fillable = ['content', 'user_id'];

    public function sender() {
        return $this->belongsTo(User::class, 'user_id');
    }
}
