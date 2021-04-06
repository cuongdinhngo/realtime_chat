<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Http\View\Composers\UserRoomComposer;
use App\Http\View\Composers\UserNotificationComposer;
use Illuminate\Support\Facades\View;

class ViewServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        View::composer(
            ['room'],
            UserRoomComposer::class
        );
        View::composer(
            ['room', 'chat'],
            UserNotificationComposer::class
        );
    }
}
