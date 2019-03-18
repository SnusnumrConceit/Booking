<?php

namespace App\Http\Controllers;

use App\Http\Resources\Room\RoomInfo;
use App\Models\Room;
use Illuminate\Http\Request;

class RoomController extends Controller
{
    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request)
    {
        try {
            $room = Room::where('number', $request->number)->count();
            if ($room) {
                throw new \Exception('Такая комната уже существует');
            }
            $room = new Room();
            $room->fill($request->input());
            $room->save();
            return response()->json([
                'status' => 'success',
                'msg' => 'Запись о комнате успешно добавлена'
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
            $rooms = ($request->page) ? Room::paginate(10) : Room::all();
            return response()->json([
                'rooms' => $rooms
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
            $rooms = new Room();
            if (isset($request->keyword)) {
                $rooms = $rooms->where('number', 'LIKE', $request->keyword.'%');
            }
            if (isset($request->filter)) {
                $filter = json_decode($request->filter);

                if (!empty($filter->name) && !empty($filter->type)) {
                    $rooms = $rooms->orderBy($filter->name, $filter->type);
                }
            }
            $rooms = $rooms->paginate(10);
            return response()->json([
                'rooms' => $rooms
            ], 200);
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
     * @param  \App\room  $room
     * @return \Illuminate\Http\Response
     */
    public function form_info(int $id)
    {
        try {
            $room = Room::findOrFail($id);
            return response()->json([
                'room' => $room
            ]);
        } catch (\Exception $error) {
            return response()->json([
                'status' => 'error',
                'msg' => $error->getMessage()
            ]);
        }
    }

    public function info(int $id)
    {
        try {
            $room = Room::with('photos')->findOrFail($id);
            return response()->json([
                'room' => new RoomInfo($room)
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
            $room = Room::where([
                'number' => $request->number,
                'price' => $request->price,
                'description' => $request->description,
            ])->count();
            if ($room) {
                throw new \Exception('Такая комната уже существует');
            }
            $room = Room::findOrFaiL($id);
            $room->fill($request->input());
            $room->save();
            return response()->json([
                'status' => 'success',
                'msg' => 'Запись о комнате успешно добавлена'
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
            $room = Room::findOrFail($id)->delete();
            return response()->json([
                'status' => 'success',
                'msg' => 'Запись о комнате успешно удалена'
            ], 200);
        } catch (\Exception $error) {
            return response()->json([
                'status' => 'error',
                'msg' => $error->getMessage()
            ]);
        }
    }
}
