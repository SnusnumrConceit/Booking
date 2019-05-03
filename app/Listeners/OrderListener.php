<?php

namespace App\Listeners;

use App\Events\OrderComplete;
use App\Mail\OrderShipped;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Support\Facades\Mail;

class OrderListener
{
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     *
     * @param  object  $event
     * @return void
     */
    public function handle(OrderComplete $event)
    {
        Mail::to($event->order->customer)->send(new OrderShipped(
            $event->order
        ));
    }
}
