<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Log;

class Audit extends Model
{
    protected $fillable = ['type', 'subject', 'user_id', 'status'];

    public function makeLog($subject, $status, $type)
    {
        try {
            $status = json_encode($status);
            $this->fill([
                'status' => $status,
                'subject' => $subject,
                'type' => $type
            ]);
            $this->save();
        } catch (\Exception $error) {
            Log::warning('This error happened in Audit. Message: '.$error->getMessage());
        }
    }
}
