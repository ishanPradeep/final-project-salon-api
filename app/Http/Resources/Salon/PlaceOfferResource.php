<?php

namespace App\Http\Resources\Salon;

use App\Helpers\Helper;
use Illuminate\Http\Resources\Json\JsonResource;

class PlaceOfferResource  extends JsonResource
{
    public static $wrap = 'place_offer';

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
        ];
    }
}
