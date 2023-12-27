<?php

namespace App\Http\Resources\Employer;

use App\Helpers\Helper;
use App\Http\Resources\Salon\SalonServiceResource;
use App\Models\Employer\Employer;
use App\Models\Salon\SalonService\SalonService;
use Illuminate\Http\Resources\Json\JsonResource;

class AllSalonServiceDetailsByEmployerIdResource extends JsonResource
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
//            $salon_service =  SalonService::find($this->salon_service_id);

        return [
            'id'=>$this->id,
            "salon_sub_service"=> $this->salonSubService,
            "salon_service"=> $this->salonSubService->salonService,
            'profile_photo'=>Helper::singleImageResponse($this->employer->user->profile_photo),
            "employer"=> $this->employer,
            "salon"=> $this->salonSubService->salonService->salon,



//            'id'=>$this->id,
//            "salon_service_id"=> $salon_service->id,
//            "salon_service_auto_approval"=> $salon_service->auto_approval,
//            "salon_service_booking_cancellation"=> $salon_service->booking_cancellation,
//            "salon_service_price"=> $salon_service->price,
//            "salon_service_hour"=> $salon_service->hour,
//            "salon_service_status"=> $salon_service->status,
//
//            "owner_id"=> $this->salon_service->salon->user->id,
//            "owner_full_name"=> $this->salon_service->salon->user->full_name,
//            "owner_contact_no"=> $this->salon_service->salon->user->contact_no,
//            "owner_profile_photo"=> $this->salon_service->salon->user->profile_photo,
//            "owner_email"=> $this->salon_service->salon->user->email,
//
//            "employer_id" => $this->employer->user->id,
//            "employer_full_name" => $this->employer->user->full_name,
//            "employer_contact_no"=> $this->employer->user->contact_no,
//            "employer_profile_photo"=> $this->employer->user->profile_photo,
//            "employer_email"=> $this->employer->user->email,
//            "employer_buffer_time"=> $this->employer->buffer_time,
//            "employer_qualifications"=> $this->employer->qualifications,
//
//            "salon_id" => $this->salon_service->salon->id,
//            "salon_name" => $this->salon_service->salon->name,
//            "salon_description" => $this->salon_service->salon->description,
//            "salon_email" => $this->salon_service->salon->email,
//            "salon_contact_number" => $this->salon_service->salon->contact_number,
//            "salon_address" => $this->salon_service->salon->address,
//            "salon_latitude" => $this->salon_service->salon->latitude,
//            "salon_longitude" => $this->salon_service->salon->longitude,
//            "salon_publish" => $this->salon_service->salon->publish,
//            "salon_salon_photo" => $this->salon_service->salon->salon_photo,
        ];
    }

}
