<?php

namespace App\Models\Salon\PlaceOffer;

use App\Models\Salon\Salon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class PlaceOffer extends Model
{

    protected $fillable = [
        'id',
        'name',
        'icon'
    ];

//    public function salons()
//    {
//        return $this->belongsToMany(Salon::class, 'company_place_offers');
//    }


}
