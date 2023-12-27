<?php

namespace App\Models\Salon;

use Illuminate\Database\Eloquent\Model;

class SalonBannerImage extends Model
{
    protected $fillable = [
        'id',
        'path',
        'salon_id'
    ];

    public function salon()
    {
        return $this->belongsTo('App\Models\Salon\Salon');
    }

}
