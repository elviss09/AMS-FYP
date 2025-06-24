<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AppointmentSlot extends Model
{
    protected $table = 'appointment_slot';
    protected $fillable = [
        'section_id',
        'day',
        'start_time'
    ];

    public $timestamps = false;  // if you don't have created_at/updated_at
}
