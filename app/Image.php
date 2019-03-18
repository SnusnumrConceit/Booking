<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class Image extends Model
{
    public static function move($from, $to)
    {
        try {
            if (!Storage::disk('local')->move($from, $to)) {
                throw new \Exception('Не удалось переместить файл');
            }
            return response()->json([
                'status' => 'success'
            ], 200);
        } catch (\Exception $error) {
            return response()->json([
                'status' => 'error',
                'msg' => $error->getMessage()
            ]);
        }
    }

    public static function upload($request)
    {
        try {
            $tmp_path = $request->file('img')->store('public/uploads/tmp');
            return response()->json([
                'status' => 'success',
                'tmp_path' => $tmp_path
            ], 200);
        } catch (\Exception $error) {
            return response()->json([
                'status' => 'error',
                'msg' => $error->getMessage()
            ]);
        }
    }

    public static function remove($image)
    {
        try {
            $image->url = 'public/'.$image->url;
            if (! Storage::disk('local')->exists($image->url)) {
                throw new \Exception('Не найден файл');
            }
            Storage::disk('local')->delete($image->url);
            return true;
        } catch (\Exception $error) {
            return response()->json([
                'status' => 'error',
                'msg' => $error->getMessage()
            ]);
        }
    }
}
