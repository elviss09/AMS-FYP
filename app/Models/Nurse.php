<<<<<<< HEAD
<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;

class Nurse extends Authenticatable
{
    protected $table = 'nurse';
    protected $primaryKey = 'staff_id';
    public $timestamps = false;

    protected $fillable = [
        'staff_id',
        'nurse_specialisation',
        'nurse_qualification'
    ];

    public function staff()
    {
        return $this->belongsTo(Staff::class, 'staff_id', 'staff_id');
    }
}
=======
<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;

class Nurse extends Authenticatable
{
    protected $table = 'nurse';
    protected $primaryKey = 'staff_id';
    public $timestamps = false;

    protected $fillable = [
        'staff_id',
        'nurse_specialisation',
        'nurse_qualification'
    ];

    public function staff()
    {
        return $this->belongsTo(Staff::class, 'staff_id', 'staff_id');
    }
}
>>>>>>> d309eaa5c66a1bed4c8e365ce485453cd53a37ab
