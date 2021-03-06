<?php

namespace App\Http\Controllers;

use App\Events\WriteAudit;
use App\Http\Resources\Photo\PhotoCollection;
use App\Http\Resources\Room\RoomCollection;
use App\Models\Room;
use App\Models\Photo;
use App\Models\PhotoRoom;
use App\Image;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PhotoController extends Controller
{
    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request)
    {
        try {
            $photo = $request->input();
            Image::move($photo['tmp_path'], $photo['destination']);
            $model = new Photo();
            $model->fill(['url' => str_replace('public/', '', $photo['destination'])]);
            $model->save();
            $room_photo = new PhotoRoom();
            $room_photo->fill([
                'photo_id' => $model->id,
                'room_id' => $photo['room']
            ]);
            $room_photo->save();
            return response()->json([
                'status' => 'success',
                'msg' => 'Фотография успешно добавлена'
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
            $rooms = Room::with('photos')->paginate(20);
            return response()->json([
                'rooms' => new RoomCollection($rooms)
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
            $rooms = $rooms->with('photos')->paginate(10);
            return response()->json([
                'rooms' => new RoomCollection($rooms)
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
            $photo = Photo::findOrFail($id);
            Image::remove($photo);
            $photo->delete();
            return response()->json([
                'status' => 'success',
                'msg'    => 'Фотография успешно удалена!'
            ]);
        } catch (\Exception $error) {
            return response()->json([
                'status' => 'error',
                'msg' => $error->getMessage()
            ]);
        }
    }

    public function getPublicPhotos()
    {
        try {
            $photos = DB::table('photos')->inRandomOrder()->limit(5)->get();
            $result = [];
            foreach ($photos as $photo) {
                array_push($result, '/storage/'.$photo->url);
            }
            return response()->json([
                'photos' => $result
            ], 200);
        } catch (\Exception $error) {
            return response()->json([
                'photos' => 'error',
                'msg' => $error->getMessage()
            ]);
        }
    }

    public function upload(Request $request)
    {
        return Image::upload($request);
    }

    public function remove(Request $request)
    {
        return Image::remove($request->file('img'));
    }
}
