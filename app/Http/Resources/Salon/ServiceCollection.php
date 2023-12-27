<?php

namespace App\Http\Resources\Salon;

use Illuminate\Http\Resources\Json\ResourceCollection;

class ServiceCollection extends ResourceCollection
{
    public static $wrap = 'service_list';

    /**
     * Transform the resource collection into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        return [
            'service_list'=> ServiceResource::collection($this->collection)
        ];
    }

}
