<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Employee extends Model
{
    protected $fillable = [
        'last_name', 'first_name',
        'middle_name', 'appointment_id',
        'birthday'
    ];

    public function appointment()
    {
        return $this->belongsTo(Appointment::class, 'appointment_id', 'id');
    }

    public function sortedAppointment($sort)
    {
        return $this->join('appointments', 'employees.appointment_id', '=', 'appointments.id')
            ->orderBy('appointments.name', $sort)->select('employees.*');
    }
}
