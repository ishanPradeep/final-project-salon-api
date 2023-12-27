<?php

namespace App\Http\Resources\Salon;
use App\Models\Salon\SalonType\SalonType;
use Illuminate\Http\Resources\Json\JsonResource;

class ServiceResource extends JsonResource
{
    public static $wrap = 'service';

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
            'name'=> $this->name,
            'icon'=> $this->icon,
//            'salon_type_id'=>$this->salon_type_id,
        ];
    }

}
