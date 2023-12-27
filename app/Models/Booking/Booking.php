<?php

namespace App\Models\Booking;

use Illuminate\Database\Eloquent\Model;

class Booking extends Model
{

    protected $fillable = [
        'id',
        'user_id',
        'salon_sub_service_id', //can't get relation. only int value ->  salon sub service all ways can delete
        'employer_id',
        'sub_service_name',
        'from_time',
        'to_time',
        'date',
        'duration',
        'price',
        'actual_price',
        'vat',
        'service_discount',
        'offer_discount',
        'status',
        'booking_cancellation'
    ];

    public function user()
    {
        return $this->belongsTo('App\Models\User\User');
    }

    public function employer()
    {
        return $this->belongsTo('App\Models\Employer\Employer');
    }

    public function salonSubService()
    {
        return $this->belongsTo('App\Models\Salon\SalonService\SalonSubService\SalonSubService');
    }


}
