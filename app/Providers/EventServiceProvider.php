<?php

namespace App\Providers;

use Illuminate\Auth\Events\Registered;
use Illuminate\Auth\Listeners\SendEmailVerificationNotification;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;
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
        ],
        // Événements de classement automatique
        \App\Events\MatchStatusUpdated::class => [
            \App\Listeners\UpdateStandingsOnMatchUpdate::class . '@handle',
        ],
        \App\Events\MatchEventOccurred::class => [
            \App\Listeners\UpdateStandingsOnMatchUpdate::class . '@handleMatchEventOccurred',
        ],
    ];

    /**
     * Register any events for your application.
     */
    public function boot(): void
    {
        // Register any events that should be fired when a model is saved, updated, etc.
        // Example: User::created(function ($user) { ... });
    }

    /**
     * Determine if events and listeners should be automatically discovered.
     */
    public function shouldDiscoverEvents(): bool
    {
        return false;
    }
}