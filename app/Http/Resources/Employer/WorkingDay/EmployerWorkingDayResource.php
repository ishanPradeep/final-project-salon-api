<?php

namespace App\Http\Resources\Employer\WorkingDay;

use App\Helpers\Helper;
use App\Http\Resources\Employer\Leave\EmployerLeaveResource;
use App\Models\Day\Day;
use App\Models\Employer\Employer;
use Illuminate\Http\Resources\Json\JsonResource;

class EmployerWorkingDayResource  extends JsonResource
{
    public static $wrap = 'employer_working_day';

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
            'day_id'=> $this->day_id,
            'day'=> Day::find($this->day_id)->name,
            'from_time'=> $this->from_time,
            'to_time'=> $this->to_time,
            'profile_photo'=>Helper::singleImageResponse($this->employer->user->profile_photo),
            'employer' => Employer::find($this->employer_id)
        ];
    }

}
