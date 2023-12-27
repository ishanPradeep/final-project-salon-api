<?php

namespace App\Models\Employer;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EmployerSalonService extends Model
{
    protected $fillable = [
        'id',
        'salon_sub_service_id',
        'employer_id',
        'buffer_time',
        'price',

    ];

    public function salonSubService()
    {
        return $this->belongsTo('App\Models\Salon\SalonService\SalonSubService\SalonSubService');
    }

    public function employer()
    {
        return $this->belongsTo('App\Models\Employer\Employer');
    }
}
