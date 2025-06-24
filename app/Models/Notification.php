<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Notification extends Model
{
    use HasFactory;

    // Link to the existing table
    protected $table = 'notifications';

    // Allow mass assignment
    protected $fillable = [
        'patient_id',
        'staff_id',
        'section_id',
        'title',
        'staff_message',
        'patient_message',
        'staff_read',
        'patient_read',
        'type',
        'created_at',
    ];

    // If your table doesn't use Laravel's automatic timestamps
    public $timestamps = false;
}
