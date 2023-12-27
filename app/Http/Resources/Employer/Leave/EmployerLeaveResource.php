<?php

namespace App\Http\Resources\Employer\Leave;

use App\Helpers\Helper;
use App\Http\Resources\Employer\EmployerResource;
use App\Models\Employer\Employer;
use Illuminate\Http\Resources\Json\JsonResource;

class EmployerLeaveResource extends JsonResource
{
    public static $wrap = 'leave';

    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        return [
            'id'=>$this->id,
            'full_day'=> $this->full_day,
            'date'=> $this->date,
            'from_time'=> $this->from_time,
            'to_time'=> $this->to_time,
            'reason'=> $this->reason,
            'status'=> $this->status,
            'employer_first_name' => $this->employer->user->fname,
            'employer_last_name' => $this->employer->user->lname, 'profile_photo'=>Helper::singleImageResponse($this->employer->user->profile_photo),
            'employer' => $this->employer
        ];
    }

}
