<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;

class Admin extends Authenticatable
{
    protected $table = 'admin';
    protected $primaryKey = 'admin_id';
    public $timestamps = false;

    protected $fillable = [
        'admin_id',
        'full_name',
        'email',
        'password',
    ];

    // If you are using Laravel authentication (guard 'admin')
    protected $hidden = [
        'password',
    ];
}
