<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class HospitalSection extends Model
{
    protected $table = 'hospital_section';
    protected $primaryKey = 'section_id';
    public $timestamps = false;
}
