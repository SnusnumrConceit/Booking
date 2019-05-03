<?php

namespace App\Providers;

use App\Events\FreeRoom;
use App\Events\OrderComplete;
use App\Events\WriteAudit;
use App\Listeners\AuditListener;
use App\Listeners\FreeRoomListener;
use App\Listeners\OrderListener;
use Illuminate\Support\Facades\Event;
use Illuminate\Auth\Events\Registered;
use Illuminate\Auth\Listeners\SendEmailVerificationNotification;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;

class EventServiceProvider extends ServiceProvider
{
    /**
     * The event listener mappings for the application.
     *
     * @var array
     */
    protected $listen = [
        Registered::class => [
            SendEmailVerificationNotification::class,
        ],
        WriteAudit::class => [
            AuditListener::class
        ],
        OrderComplete::class => [
            OrderListener::class
        ],
        FreeRoom::class =>  [
            FreeRoomListener::class
        ]
    ];

    /**
     * Register any events for your application.
     *
     * @return void
     */
    public function boot()
    {
        parent::boot();

        //
    }
}
