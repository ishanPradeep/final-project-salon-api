<?php

namespace App\Models\Salon\SalonService\SalonSubService;

use App\Models\Salon\SalonService\SalonService;
use Illuminate\Database\Eloquent\Model;

class SalonSubService extends Model
{
    protected $fillable = [
        'salon_service_id',
        'sub_service_name',
        'id',
        'hour',
        'status',
        'auto_approval',
        'booking_cancellation',
        'image'
    ];

    public function salonService()
    {
        return $this->belongsTo(SalonService::class);
    }
}
