<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Carbon\Carbon;

class PublicHoliday extends Model
{
    use HasFactory;

    protected $table = 'public_holidays';
    protected $primaryKey = 'id';
    public $timestamps = false;

    protected $fillable = [
        'holiday_date',
        'description',
    ];

    // Accessors for formatted output
    public function getFormattedDateAttribute()
    {
        return Carbon::parse($this->holiday_date)->format('d-m-Y');
    }

    public function getDayNameAttribute()
    {
        return Carbon::parse($this->holiday_date)->format('l');
    }
}
