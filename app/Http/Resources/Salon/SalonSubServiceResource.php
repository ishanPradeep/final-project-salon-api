<?php

namespace App\Http\Resources\Salon;

use App\Helpers\Helper;
use App\Models\Salon\Salon;
use App\Models\Salon\SalonService\SalonService;
use App\Models\Salon\SalonType\SalonType;
use Illuminate\Http\Resources\Json\JsonResource;

class SalonSubServiceResource extends JsonResource
{
    public static $wrap = 'salon_sub_service';

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
            'sub_service_name' => $this->sub_service_name,
            'salon_service_id'=> $this->salon_service_id,
            'salon_service' => SalonService::find($this->salon_service_id),
            'image'=>Helper::singleImageResponse($this->image),
            'status'=> $this->status,
            'auto_approval' => $this->auto_approval,
            'hour'=> $this->hour,
            'booking_cancellation'=> $this->booking_cancellation
        ];
    }

}
