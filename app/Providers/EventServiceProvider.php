<?php

namespace App\Providers;

use App\Events\ProjectActualityAddedEvent;
use App\Events\ProjectAuctionBidNewEvent;
use App\Events\ProjectCommentAddedEvent;
use App\Events\ProjectCreatedEvent;
use App\Events\ProjectDocumentAddedEvent;
use App\Events\ProjectFinishedEvent;
use App\Events\ProjectFixedPricePrincipalPayNoFirstEvent;
use App\Events\ProjectOfferThePriceBidsEndEvent;
use App\Events\ProjectPreliminaryInterestBidsEndEvent;
use App\Events\RegisteredTranslatorEvent;
use App\Listeners\AfterUserRegistered;
use App\Listeners\AfterUserVerified;
use App\Listeners\LogErrorListener;
use App\Listeners\ProjectActualityAddedListener;
use App\Listeners\ProjectAuctionBidNewListener;
use App\Listeners\ProjectCommentAddedListener;
use App\Listeners\ProjectCreatedListener;
use App\Listeners\ProjectDocumentAddedListener;
use App\Listeners\ProjectFinishedListener;
use App\Listeners\ProjectFixedPricePrincipalPayNoFirstListener;
use App\Listeners\ProjectOfferThePriceBidsEndListener;
use App\Listeners\ProjectPreliminaryInterestBidsEndListener;
use Illuminate\Auth\Events\Registered;
use App\Events\RegisteredAdminEvent;
use App\Events\RegisteredAdvisorEvent;
use Illuminate\Auth\Events\Verified;
use Illuminate\Auth\Listeners\SendEmailVerificationNotification;
use App\Listeners\SendEmailVerificationNotificationAdmin;
use App\Listeners\SendEmailVerificationNotificationAdvisor;
use App\Listeners\SendEmailVerificationNotificationTranslator;
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
        RegisteredAdminEvent::class => [
            SendEmailVerificationNotificationAdmin::class,
        ],
        RegisteredAdvisorEvent::class => [
            SendEmailVerificationNotificationAdvisor::class,
        ],
        RegisteredTranslatorEvent::class => [
            SendEmailVerificationNotificationTranslator::class,
        ],
        ProjectCreatedEvent::class => [
            ProjectCreatedListener::class,
        ],
        ProjectDocumentAddedEvent::class => [
            ProjectDocumentAddedListener::class,
        ],
        ProjectCommentAddedEvent::class => [
            ProjectCommentAddedListener::class,
        ],
        ProjectActualityAddedEvent::class => [
            ProjectActualityAddedListener::class,
        ],
        ProjectAuctionBidNewEvent::class => [
            ProjectAuctionBidNewListener::class,
        ],
        ProjectOfferThePriceBidsEndEvent::class => [
            ProjectOfferThePriceBidsEndListener::class,
        ],
        ProjectPreliminaryInterestBidsEndEvent::class => [
            ProjectPreliminaryInterestBidsEndListener::class,
        ],
        ProjectFixedPricePrincipalPayNoFirstEvent::class => [
            ProjectFixedPricePrincipalPayNoFirstListener::class,
        ],
        ProjectFinishedEvent::class => [
            ProjectFinishedListener::class,
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
