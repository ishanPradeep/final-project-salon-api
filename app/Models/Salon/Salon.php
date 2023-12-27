<?php

namespace App\Models\Salon;

use App\Models\Salon\PlaceOffer\PlaceOffer;
use App\Models\Salon\SalonService\SalonService;
use App\Models\Salon\SalonType\SalonType;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Salon extends Model
{
    protected $fillable = [
        'id',
        'user_id',
        'salon_type_id',
        'name',
        'description',
        'email',
        'contact_number',
        'address',
        'latitude',
        'longitude',
        'publish',
        'salon_logo',
    ];

    public function user()
    {
        return $this->belongsTo('App\Models\User\User');
    }

    public function salonType()
    {
        return $this->belongsTo('App\Models\Salon\SalonType\SalonType');
    }

    public function salonBannerImages()
    {
        return $this->hasMany('App\Models\Salon\SalonBannerImage');
    }

    public function salonOpenEndTimes()
    {
        return $this->hasMany('App\Models\Salon\SalonOpenEndTime');
    }

    public function placeOffers()
    {
        return $this->belongsToMany(PlaceOffer::class, 'salon_place_offers');
    }

    public function salonReviews()
    {
        return $this->hasMany('App\Models\Review\SalonReview');
    }
}
