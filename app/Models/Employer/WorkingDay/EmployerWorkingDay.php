<?php

namespace App\Models\Employer\WorkingDay;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EmployerWorkingDay extends Model
{
    protected $fillable = [
        'day_id',
        'employer_id',
        'from_time',
        'to_time'
    ];

    public function employer()
    {
        return $this->belongsTo('App\Models\Employer\Employer');
    }

    public function day()
    {
        return $this->belongsTo('App\Models\Day\Day');
    }
}
