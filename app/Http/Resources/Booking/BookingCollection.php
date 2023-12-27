<?php

namespace App\Http\Resources\Booking;

use Illuminate\Http\Resources\Json\ResourceCollection;

class BookingCollection extends ResourceCollection
{
    public static $wrap = 'booking_list';

    /**
     * Transform the resource collection into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        return [
            'booking_list'=> BookingResource::collection($this->collection)
        ];
    }

}
