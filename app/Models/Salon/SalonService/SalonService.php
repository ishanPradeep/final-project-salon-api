<?php

namespace App\Models\Salon\SalonService;

use App\Models\Employer\Employer;
use App\Models\Salon\Salon;
use App\Models\Salon\Service\Service;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class SalonService extends Model
{
    protected $fillable = [
        'salon_id',
        'service_id',
        'salon_service_name',
        'id'
    ];
    public function salon()
    {
        return $this->belongsTo(Salon::class);
    }
    public function service()
    {
        return $this->belongsTo(Service::class);
    }

    public function employers(): BelongsToMany
    {
        return $this->belongsToMany(Employer::class, 'employe_salon_services');
    }

    public function bannerImages()
    {
        return $this->hasMany('App\Models\Salon\SalonService\BannerImage\SalonServiceBannerImage');
    }

    public function salonSubService()
    {
        return $this->hasMany('App\Models\Salon\SalonService\SalonSubService\SalonSubService');
    }


}
