<?php

namespace App\Http\Resources\Employer;

use App\Helpers\Helper;
use App\Http\Resources\Salon\SalonCollection;
use App\Http\Resources\Salon\SalonServiceCollection;
use App\Http\Resources\Salon\SalonSubServiceCollection;
use App\Models\Employer\Employer;
use App\Models\Salon\SalonService\SalonService;
use App\Models\Salon\SalonService\SalonSubService\SalonSubService;
use App\Repositories\Salon\Interface\SalonSubServiceRepositoryInterface;
use App\Repositories\Salon\SalonSubServiceRepository;
use Illuminate\Http\Resources\Json\JsonResource;

class EmployerSalonServiceDetailsBySalonServiceIdResource extends JsonResource
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
            'price'=>$this->price,
            'buffer_time'=>$this->buffer_time,
            "salon_sub_service"=> $this->salonSubService,
            "salon_service"=> $this->salonSubService->salonService,
            "employer_id"=> $this->employer->id,
            "employer_user_id"=> $this->employer->user->id,
            "employer_fname"=> $this->employer->user->fname,
            "employer_lname"=> $this->employer->user->lname,
            "employer_qualifications"=> $this->employer->qualifications,
            "employer_publish"=> $this->employer->publish,
            "employer_email"=> $this->employer->user->email,
            "employer_contact_no"=> $this->employer->user->contact_no,
            "employer_profile_photo"=> Helper::singleImageResponse($this->employer->user->profile_photo),
            "salon"=> $this->salonSubService->salonService->salon,
        ];
    }

}
