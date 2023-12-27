<?php

namespace App\Models\Employer\Leave;

use Illuminate\Database\Eloquent\Model;

class EmployerLeave extends Model
{
    protected $fillable = [
        'id',
        'full_day',
        'date',
        'from_time',
        'to_time',
        'employer_id',
        'status'
    ];

    public function employer()
    {
        return $this->belongsTo('App\Models\Employer\Employer');
    }
}
