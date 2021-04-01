<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Notifications\MessageWasPosted;
use App\Models\User;
use Illuminate\Support\Facades\Mail;

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
        logger(__METHOD__);
        $user = User::find(1);
        $user->notify(new MessageWasPosted($user, "Good morning!"));
        logger('-----------------------------');
        // foreach ($user->notifications as $notification) {
        //     dump($notification->type);
        // }
    }
}
