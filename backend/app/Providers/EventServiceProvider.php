<?php

namespace App\Providers;

use App\Events\ChargeUpdated;
use App\Events\ColocationCreated;
use App\Events\ColocationUpdated;
use App\Listeners\CreateChargesForColocation;
use App\Listeners\UpdateChargeAmountAffectedAttribute;
use App\Listeners\UpdateChargeRentAmount;
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
        ChargeUpdated::class => [
            UpdateChargeAmountAffectedAttribute::class,
        ],
        ColocationCreated::class => [
            CreateChargesForColocation::class,
        ],
        ColocationUpdated::class => [
            UpdateChargeRentAmount::class,
        ],
        Registered::class => [
            SendEmailVerificationNotification::class,
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
