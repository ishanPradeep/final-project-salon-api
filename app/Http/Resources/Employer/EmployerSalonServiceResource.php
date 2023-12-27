<?php

namespace App\Http\Resources\Employer;

use App\Helpers\Helper;
use App\Http\Controllers\API\Employer\EmployerSalonServiceController;
use App\Http\Resources\Employer\Leave\EmployerLeaveResource;
use App\Http\Resources\Employer\WorkingDay\EmployerWorkingDayResource;
use App\Http\Resources\Salon\SalonServiceCollection;
use App\Models\Employer\Employer;
use App\Models\Salon\SalonService\SalonService;
use App\Models\Salon\SalonService\SalonSubService\SalonSubService;
use App\Models\User\User;
use App\Repositories\Employer\Employer\EmployerRepository;
use App\Repositories\Salon\SalonServiceRepository;
use Illuminate\Http\Resources\Json\JsonResource;

class EmployerSalonServiceResource extends JsonResource
{
    public static $wrap = 'employer_salon_service';

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
            'buffer_time'=>$this->buffer_time,
            'price'=>$this->price,
            'employer' => Employer::find($this->employer_id),
            'employer_fname' => User::find($this->employer->user->id)->fname,
            'employer_lname' => User::find($this->employer->user->id)->lname,
            'profile_photo'=>Helper::singleImageResponse($this->employer->user->profile_photo),
            'salon_sub_service' => SalonSubService::find($this->salon_sub_service_id)
        ];
    }

}
