<?php

namespace App\Http\Resources\Salon;

use Illuminate\Http\Resources\Json\ResourceCollection;

class SalonServiceCollection extends ResourceCollection
{
    public static $wrap = 'salon_type_list';

    /**
     * Transform the resource collection into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        return [
            'salon_type_list'=> SalonServiceResource::collection($this->collection)
        ];
    }

}
