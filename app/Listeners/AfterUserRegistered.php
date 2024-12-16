<?php

namespace App\Listeners;

use App\Models\User;
use App\Services\UsersService;

class AfterUserRegistered
{
    /**
     * Create the event listener.
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     */
    public function handle(object $event): void
    {
        // $event->user
        if(User::all()->count() === 1 && User::where('id', '!=', $event->user->id)->count() === 0) {
            $event->user->owner = true;
            $event->user->superadmin = true;
            $event->user->save();
        }

        (new UsersService())->setNotifications($event->user);
    }
}
