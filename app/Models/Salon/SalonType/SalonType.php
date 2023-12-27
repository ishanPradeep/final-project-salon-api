<?php

namespace App\Models\Salon\SalonType;

use App\Models\Salon\Service\Service;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class SalonType extends Model
{
    protected $fillable = [
        'id',
        'title',
        'subtitle',
        'icon'
    ];
    public function services()
    {
        return $this->hasMany('App\Models\Salon\Service\Service');
    }

}
