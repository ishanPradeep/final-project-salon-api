<?php

namespace App\Http\Resources\Salon;

use Illuminate\Http\Resources\Json\ResourceCollection;

class SalonCollection extends ResourceCollection
{
    public static $wrap = 'salon_list';

    /**
     * Transform the resource collection into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        return [
            'salon_list'=> SalonResource::collection($this->collection)
        ];
    }

}
