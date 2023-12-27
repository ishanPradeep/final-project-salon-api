<?php

namespace App\Models\Employer;

use App\Models\Salon\SalonService\SalonService;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Employer extends Model
{
    protected $fillable = [
        'user_id',
        'salon_id',
        'qualifications',
        'publish'
    ];

    public function salon()
    {
        return $this->belongsTo('App\Models\Salon\Salon');
    }

    public function user()
    {
        return $this->belongsTo('App\Models\User\User');
    }

    public function employer_leaves()
    {
        return $this->hasMany('App\Models\Employer\Leave\EmployerLeave');
    }

    public function salon_services(): BelongsToMany
    {
        return $this->belongsToMany(SalonService::class, 'employe_salon_services');
    }

    public function employer_working_day()
    {
        return $this->hasMany('App\Models\Employer\WorkingDay\EmployerWorkingDay');
    }

    public function employerReviews()
    {
        return $this->hasMany('App\Models\Review\EmployerReview');
    }

}
