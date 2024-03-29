<?php

namespace App\Models\User;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Spatie\Permission\Traits\HasRoles;

class User extends Authenticatable
{
    use HasApiTokens;
    use HasFactory;
    use Notifiable;
    use HasRoles;
    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'fname',
        'lname',
        'address',
        'latitude',
        'longitude',
        'contact_no',
        'password',
        'profile_photo',
        'email',
        'have_salon',
        'active'
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
        'two_factor_recovery_codes',
        'two_factor_secret',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    /**
     * The accessors to append to the model's array form.
     *
     * @var array<int, string>
     */
//    protected $appends = [
//        'profile_photo',
//    ];

    public function userLevel()
    {
        return $this->belongsTo('App\Models\User\UserLevel');
    }

    public function salonReviews()
    {
        return $this->hasMany('App\Models\Review\SalonReview');
    }

    public function employerReviews()
    {
        return $this->hasMany('App\Models\Review\EmployerReview');
    }

}
