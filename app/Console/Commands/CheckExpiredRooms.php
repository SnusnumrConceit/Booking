<?php

namespace App\Console\Commands;

use App\Models\Order;
use App\Models\Room;
use Carbon\Carbon;
use Illuminate\Console\Command;

class CheckExpiredRooms extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'rooms:expired';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Checking rooms on expired';

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
        $rooms = Room::where('free', 0)->get();
        foreach ($rooms as $room) {
            $order = Order::where('room_id', $room->id)->latest('created_at')->first();
            $expire_date = (new Carbon($order->note_date))->addDays($order->days);
            if ($expire_date <= Carbon::now() ) {
                $room->free = 1;
                $room->save();
            }
        }
    }
}
