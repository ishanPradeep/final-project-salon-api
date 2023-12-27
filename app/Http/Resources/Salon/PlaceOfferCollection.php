<?php

namespace App\Http\Resources\Salon;

use Illuminate\Http\Resources\Json\ResourceCollection;

class PlaceOfferCollection extends ResourceCollection
{
    public static $wrap = 'place_offer_list';

    /**
     * Transform the resource collection into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        return [
            'place_offer_list'=> PlaceOfferResource::collection($this->collection)
        ];
    }

}
