<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class Image extends Model
{
    public function move($from, $to)
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

    public function upload($to)
    {
        try {
            if (!Storage::disk('local')->put($to)) {
                throw new \Exception('Не удалось загрузить файл');
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

    public function remove($path)
    {
        try {
            if (! Storage::disk('local')->exists($path)) {
                throw new \Exception('Не найден файл');
            }
            Storage::disk('local')->delete($path);
            return true;
        } catch (\Exception $error) {
            return response()->json([
                'status' => 'error',
                'msg' => $error->getMessage()
            ]);
        }
    }
}
