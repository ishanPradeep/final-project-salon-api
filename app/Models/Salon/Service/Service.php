<?php

namespace App\Models\Salon\Service;

use App\Models\Salon\Salon;
use App\Models\Salon\SalonType\SalonType;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Service extends Model
{
    protected $fillable = [
        'id',
        'salon_type_id',
        'name',
        'icon'
    ];


    public function salonType()
    {
        return $this->belongsTo('App\Models\Salon\SalonType\SalonType');
    }

    public function salon(): BelongsToMany
    {
        return $this->belongsToMany(Salon::class, 'salon_services');
    }

}
