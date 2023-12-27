<?php

namespace App\Models\Review;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EmployerReview extends Model
{
    protected $fillable = [
        'id',
        'user_id',
        'employer_id',
        'review',
        'rating'
    ];

    public function user()
    {
        return $this->belongsTo('App\Models\User\User');
    }
    public function employer()
    {
        return $this->belongsTo('App\Models\Employer\Employer');
    }
}
