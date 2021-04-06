<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Notifications\MessageWasPosted;
use App\Models\User;
use App\Models\Room;
use Illuminate\Support\Facades\Mail;
use App\Http\Controllers\Room\RoomService;

class TestNotifcation extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'test:notify';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $user = User::find(1);
        $room = Room::find(3);
        $user->notify(new MessageWasPosted($user, $room->id));
    }
}
