<?php

namespace App\Http\Resources\Salon;

use Illuminate\Http\Resources\Json\ResourceCollection;

class BannerImageCollection extends ResourceCollection
{
    public static $wrap = 'banner_image_list';

    /**
     * Transform the resource collection into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        return [
            'banner_image_list'=> BannerImagesResource::collection($this->collection)
        ];
    }

}
