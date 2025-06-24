<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;

class Patient extends Authenticatable
{
    protected $table = 'patients';
    protected $primaryKey = 'patient_id';
    public $incrementing = false;
    protected $keyType = 'string';
    public $timestamps = false; // ✅ disable timestamps

    protected $fillable = [
        'patient_id',
        'full_name',
        'email',
        'password',
        'date_of_birth',
        'gender',
        'phone_no',
        'emergency_contact',
        'emergency_relationship',
        'height',
        'weight',
        'blood_type',
        'penicillin_allergy',
        'allergy_reaction',
        'notify_1day',
        'notify_3days',
        'notify_1week',
        // add any other columns you have
    ];
}
