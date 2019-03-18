<?php

namespace App\Mail;

use App\Models\Room;
use App\User;
use App\Models\Order;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class OrderShipped extends Mailable
{
    use Queueable, SerializesModels;

    public $order;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($order)
    {
        $this->order = $order;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->from('booking.ru@world.info')->markdown('emails.orders.shipped')
            ->with([
                'order_number' => $this->order->room->number,
                'order_days' => $this->order->days,
                'customer_name' => $this->order->customer->last_name.' '.$this->order->customer->first_name
            ])->subject('Заказ на '. config('app.name'));
    }
}
