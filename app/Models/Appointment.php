<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Appointment extends Model
{
    protected $table = 'appointments';
    protected $primaryKey = 'appointment_id';
    public $timestamps = false;

    protected $fillable = [
        'patient_id',
        'assigned_doctor',
        'appointment_date',
        'appointment_time',
        'appointment_type',
        'appointment_location',
        'refer_to',
        'referral_letter',
        'status',
        'status_details',
        'created_at',
        'approved_by',
        'approved_date',
        'reminded_1day',
        'reminded_3days',
        'reminded_1week',
    ];

    public function patient()
    {
        return $this->belongsTo(Patient::class, 'patient_id', 'patient_id');
    }

    public function section()
    {
        return $this->belongsTo(HospitalSection::class, 'appointment_location', 'section_id');
    }
}
