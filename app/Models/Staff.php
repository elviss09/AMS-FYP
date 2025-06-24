<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;

class Staff extends Authenticatable
{
    protected $table = 'staff'; // your table name
    protected $primaryKey = 'staff_id'; // your primary key if not 'id'
    public $timestamps = false; // if you don't have created_at/updated_at

    protected $fillable = [
        'staff_id',
        'full_name',
        'date_of_birth',
        'age',
        'gender',
        'phone_no',
        'email',
        'emergency_contact',
        'emergency_relationship',
        'role',
        'position',
        'working_section',
    ];

    protected $hidden = [
        'password',
    ];

    public function doctor()
    {
        return $this->hasOne(Doctor::class, 'staff_id', 'staff_id');
    }

    // Relationship to Nurse (if exists)
    public function nurse()
    {
        return $this->hasOne(Nurse::class, 'staff_id', 'staff_id');
    }
}
