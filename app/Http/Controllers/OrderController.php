<?php

namespace App\Http\Controllers;

use App\Events\FreeRoom;
use App\Events\OrderComplete;
use App\Http\Requests\Order\OrderFormRequest;
use App\Http\Resources\Order\OrderCollection;
use App\Mail\OrderShipped;
use Illuminate\Support\Facades\Mail;
use App\Models\Order;
use App\Models\Room;
use App\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class OrderController extends Controller
{
    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(OrderFormRequest $request)
    {
        try {
            $request->validated();
            if (empty($request->status)) {
                $request->status = 2;
            }
            if (empty($request->user_id)) {
                $request->customer = auth()->user()->id;
            }
            $order = Order::where([
                'user_id' => $request->customer,
                'room_id' => $request->room,
                'status' => $request->status
            ])->count();
            if ($order) {
                throw new \Exception('Такой заказ уже существует');
            }
            $order = new Order();
            $order->fill([
                'user_id' => $request->customer,
                'room_id' => $request->room,
                'status' => $request->status,
                'note_date' => $this->convertDate($request->note_date, $request->note_time),
                'days' => $request->days
            ]);
            $order->save();
            if ($order->status == 2) {
                event(new FreeRoom(0, $order->room_id));
            }
            $mail_order = $order->with(['customer', 'room'])->findOrFail($order->id);
            event(new OrderComplete($mail_order));
//            Mail::to($mail_order->customer)->send(new OrderShipped(
//                $mail_order
//            ));
            return response()->json([
                'status' => 'success',
                'msg' => 'Заказ успешно добавлен'
            ], 200);
        } catch (\Exception $error) {
            return response()->json([
                'status' => 'error',
                'msg' => $error->getMessage()
            ]);
        }
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        try {
         $orders = Order::with(['customer', 'room'])->paginate(10);
         return response()->json([
             'orders' => new OrderCollection($orders)
         ], 200);
        } catch (\Exception $error) {
            return response()->json([
                'status' => 'error',
                'msg' => $error->getMessage()
            ]);
        }
    }

    public function search(Request $request)
    {
        try {
            $orders = new Order();
            if (! empty($request->keyword)) {
                $orders = (new Order())->searchByCustomer($request->keyword);
            }
            if (isset($request->filter)) {
                $filter = json_decode($request->filter);

                if (!empty($filter->name) && !empty($filter->type)) {
                    switch ($filter->name) {
                        case 'last_name': $orders = (new Order())->sortByCustomer($filter->type); break;
                        case 'number': $orders = (new Order())->sortByRoom('number', $filter->type); break;
                        case 'price': $orders = (new Order())->sortByRoom('price', $filter->type); break;
                        default: $orders = $orders->orderBy($filter->name, $filter->type); break;
                    }
                }
            }
            $orders = $orders->paginate(10);
            return response()->json([
                'orders' => new OrderCollection($orders)
            ]);
        } catch (\Exception $error) {
            return response()->json([
                'status' => 'error',
                'msg' => $error->getMessage()
            ]);
        }
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function edit(int $id)
    {
        try {
            $order = Order::with(['customer', 'room'])->findOrFail($id);
            return response()->json([
                'order' => $order,
            ], 200);
        } catch (\Exception $error) {
            return response()->json([
                'status' => 'error',
                'msg' => $error->getMessage()
            ]);
        }
    }

    public function form_info(Request $request)
    {
        try {
            $rooms = (new Room())->free()->get();
            if (! empty($request->id)) {
                $order = Order::findOrFail($request->id);
                $room = Room::findOrFail($order->room_id);
                $rooms->push($room);
            }
            return response()->json([
                'rooms' => $rooms,
                'customers' => User::all()
            ], 200);
        } catch (\Exception $error) {
            return response()->json([
                'status' => 'error',
                'msg' => $error->getMessage()
            ]);
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, int $id)
    {
        try {
//            $order = Order::where([
//                'user_id' => $request->customer,
//                'room_id' => $request->room,
//                'status' => $request->status
//            ])->count();
//            if ($order) {
//                throw new \Exception('Такой заказ уже существует');
//            }
            $order = Order::with(['customer', 'room'])->findOrFail($id);
            $order->fill($request->input());
            $order->note_date = $this->convertDate($request->note_date, $request->note_time);
            $order->save();
            if ($order->status == 3) {
                event(new FreeRoom(1, $order->room_id));
            }
            return response()->json([
                'status' => 'success',
                'msg' => 'Заказ успешно обновлён'
            ], 200);
        } catch (\Exception $error) {
            return response()->json([
                'status' => 'error',
                'msg' => $error->getMessage()
            ]);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @return \Illuminate\Http\Response
     */
    public function destroy(int $id)
    {
        try {
            $order = Order::findOrFail($id);
            Room::where('id', $order->room_id)->update([
                'free' => 1
            ]);
            $order->delete();
            return response()->json([
                'status' => 'success',
                'msg' => 'Заказ успешно удалён'
            ], 200);
        } catch (\Exception $error) {
            return response()->json([
                'status' => 'error',
                'msg' => $error->getMessage()
            ]);
        }
    }

    public function publish(Request $request)
    {
        $data = (object )[
            'room'      =>  $request->room_id,
            'days'      =>  $request->days,
            'note_date' =>  $request->note_date,
            'note_time' =>  $request->note_time
        ];
        if (empty($data->status)) {
            $data->status = 1;
        }
        if (empty($data->user_id)) {
            $data->customer = auth()->user()->id;
        }
        $order = Order::where([
            'user_id' => $data->customer,
            'room_id' => $data->room,
            'status' => $data->status
        ])->count();
        if ($order) {
            throw new \Exception('Такой заказ уже существует');
        }
        $order = new Order();
        $order->fill([
            'user_id' => $data->customer,
            'room_id' => $data->room,
            'status' => $data->status,
            'note_date' => $this->convertDate($data->note_date, $data->note_time),
            'days' => $data->days
        ]);
        $order->save();
        event(new FreeRoom(0, $order->room_id));
        $mail_order = $order->with(['customer', 'room'])->findOrFail($order->id);
        event(new OrderComplete($mail_order));
//        Mail::to($mail_order->customer)->send(new OrderShipped(
//            $mail_order
//        ));
        return response()->json([
            'status' => 'success',
            'msg' => 'Заказ успешно добавлен'
        ], 200);
//        $this->create($needless_request);
    }

    public function convertDate($date, $time)
    {
        return Carbon::parse($date)->format('Y-m-d').' '.$time['HH'].':'.$time['mm'];
    }
}
