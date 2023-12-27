<?php

namespace App\Models\Review;

use Illuminate\Database\Eloquent\Model;

class SalonReview extends Model
{
    protected $fillable = [
        'id',
        'user_id',
        'salon_id',
        'review',
        'rating'
    ];

    public function user()
    {
        return $this->belongsTo('App\Models\User\User');
    }

    public function salon()
    {
        return $this->belongsTo('App\Models\Salon\Salon');
    }
}
