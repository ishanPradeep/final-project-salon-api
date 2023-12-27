<?php

namespace App\Http\Resources\Salon;
use Illuminate\Http\Resources\Json\JsonResource;

class SalonTypeResource extends JsonResource
{
    public static $wrap = 'salon_type';

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
            'title'=> $this->title,
            'subtitle'=> $this->subtitle,
            'icon'=> $this->icon,
            'services' => ServiceResource::collection($this->services)
        ];
    }
}
