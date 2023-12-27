<?php

namespace App\Models\Salon\PlaceOffer;

use Illuminate\Database\Eloquent\Model;

class SalonPlaceOffer extends Model
{
    protected $fillable = [
        'id',
        'salon_id',
        'place_offer_id'
    ];

}
