<?php

namespace App\Http\Resources\Booking;

use App\Helpers\Helper;
use App\Models\Employer\Employer;
use App\Models\Salon\SalonService\SalonSubService\SalonSubService;
use Illuminate\Http\Resources\Json\JsonResource;

class BookingResource extends JsonResource
{
    public static $wrap = 'booking';

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
            'from_time'=>$this->from_time,
            'to_time'=>$this->to_time,
            'date'=>$this->date,
            'duration'=>$this->duration,
            'price'=>$this->price,
            'actual_price'=>$this->actual_price,
            'vat'=>$this->vat,
            'service_discount'=>$this->service_discount,
            'status'=>$this->status,
            'booking_cancellation'=>$this->booking_cancellation,
            "salon_sub_service"=> SalonSubService::find($this->salon_sub_service_id),
            "employer_fname"=>$this->employer->user->fname,
            "employer_lname"=>$this->employer->user->lname,
            'profile_photo'=>Helper::singleImageResponse($this->employer->user->profile_photo),
            "employer"=> Employer::find($this->employer_id),
            "salon"=> $this->salonSubService->salonService->salon,
            "user"=> $this->user,

        ];
    }

}
