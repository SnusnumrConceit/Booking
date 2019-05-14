<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Log;

class Audit extends Model
{
    protected $fillable = ['type', 'subject', 'user_id', 'status'];
    protected $table = 'audit';

    public function event()
    {
        return $this->belongsTo(AuditEvent::class, 'type', 'id');
    }

    public function makeLog($subject, $status, $type, $user_id)
    {
        try {
//            $status = json_encode($status);
            $log = (new Audit())->fill([
                'status' => $this->setStatus($status),
                'subject' => json_encode($subject),
                'type' => $type,
                'user_id' => (empty($user_id)) ? auth()->id() : $user_id
            ]);
            $log->save();
        } catch (\Exception $error) {
            Log::warning('This error happened in Audit. Message: '.$error->getMessage());
        }
    }

    public function setStatus($status)
    {
        switch ($status) {
            case 1: return json_encode((object) ['status' => 'success']);
            case 2: return json_encode((object) ['status' => 'error']);
        }
    }
}
