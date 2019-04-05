<?php

namespace App\Http\Controllers;

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
                $request->status = 1;
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
            $mail_order = $order->with(['customer', 'room'])->findOrFail($order->id);
            Mail::to($mail_order->customer)->send(new OrderShipped(
                $mail_order
            ));
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
//            'orders' => new OrderCollection($orders)
         return response()->json([]);
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

    public function form_info()
    {
        try {
            return response()->json([
                'rooms' => Room::all(),
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
            $order = Order::where([
                'user_id' => $request->customer,
                'room_id' => $request->room,
                'status' => $request->status
            ])->count();
            if ($order) {
                throw new \Exception('Такой заказ уже существует');
            }
            $order = Order::with(['customer', 'room'])->findOrFail($id);
            $order->fill($request->input());
            $order->save();
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
            Order::findOrFail($id)->delete();
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

    public function convertDate($date, $time)
    {
        return Carbon::parse($date)->format('Y-m-d').' '.$time['HH'].':'.$time['mm'];
    }
}
