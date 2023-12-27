<?php

namespace App\Http\Resources\Salon;

use App\Helpers\Helper;
use Illuminate\Http\Resources\Json\JsonResource;

class BannerImagesResource extends JsonResource
{
    public static $wrap = 'banner_image';

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
            'salon_service_id'=> $this->salon_service_id,
            'path' => Helper::imageResponse($this->path),
        ];
    }

}
