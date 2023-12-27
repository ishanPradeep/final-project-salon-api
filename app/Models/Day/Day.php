<?php

namespace App\Models\Day;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Day extends Model
{
    protected $fillable = [
        'id',
        'name',
    ];

    public function employer_working_days() {
        return $this->hasMany('App\Models\Employer\WorkingDay\EmployerWorkingDay');
    }

}
