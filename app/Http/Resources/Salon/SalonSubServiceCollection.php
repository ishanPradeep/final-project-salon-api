<?php

namespace App\Http\Resources\Salon;

use Illuminate\Http\Resources\Json\ResourceCollection;

class SalonSubServiceCollection extends ResourceCollection
{
    public static $wrap = 'salon_sub_service_list';

    /**
     * Transform the resource collection into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        return [
            'salon_sub_service_list'=> SalonSubServiceResource::collection($this->collection)
        ];
    }

}
