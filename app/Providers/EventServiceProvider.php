<?php

namespace App\Providers;

use App\Listeners\AfterUserRegistered;
use App\Listeners\AfterUserVerified;
use App\Listeners\LogErrorListener;
use Illuminate\Auth\Events\Registered;
use App\Events\RegisteredAdmin;
use App\Events\RegisteredAdvisor;
use Illuminate\Auth\Events\Verified;
use Illuminate\Auth\Listeners\SendEmailVerificationNotification;
use App\Listeners\SendEmailVerificationNotificationAdmin;
use App\Listeners\SendEmailVerificationNotificationAdvisor;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;
use Illuminate\Log\Events\MessageLogged;
use Illuminate\Support\Facades\Event;

class EventServiceProvider extends ServiceProvider
{
    /**
     * The event to listener mappings for the application.
     *
     * @var array<class-string, array<int, class-string>>
     */
    protected $listen = [
        Registered::class => [
            SendEmailVerificationNotification::class,
            AfterUserRegistered::class,
        ],
        Verified::class => [
            AfterUserVerified::class,
        ],
        RegisteredAdmin::class => [
            SendEmailVerificationNotificationAdmin::class,
        ],
        RegisteredAdvisor::class => [
            SendEmailVerificationNotificationAdvisor::class,
        ],
        MessageLogged::class => [
            LogErrorListener::class,
        ],
    ];

    /**
     * Register any events for your application.
     */
    public function boot(): void
    {
        //
    }

    /**
     * Determine if events and listeners should be automatically discovered.
     */
    public function shouldDiscoverEvents(): bool
    {
        return false;
    }
}
