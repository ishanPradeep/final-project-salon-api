<?php

namespace App\Models\Salon;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SalonOpenEndTime extends Model
{
    protected $fillable = [
        'id',
        'day_id',
        'salon_id',
        'from_time',
        'to_time',
        'is_open'
    ];

    public function salon()
    {
        return $this->belongsTo('App\Models\Salon\Salon');
    }
}
