<?php

namespace App\Models\Salon\SalonService\BannerImage;

use Illuminate\Database\Eloquent\Model;

class SalonServiceBannerImage extends Model
{
    protected $fillable = [
        'id',
        'path',
        'salon_service_id'
    ];

    public function salon_service()
    {
        return $this->belongsTo('App\Models\Salon\SalonService\SalonService');
    }}
