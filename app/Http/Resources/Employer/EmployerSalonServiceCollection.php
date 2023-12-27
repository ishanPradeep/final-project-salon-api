<?php

namespace App\Http\Resources\Employer;

use Illuminate\Http\Resources\Json\ResourceCollection;

class EmployerSalonServiceCollection extends ResourceCollection
{
    public static $wrap = 'employer_salon_service_list';

    /**
     * Transform the resource collection into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        return [
            'employer_salon_service_list'=> EmployerSalonServiceResource::collection($this->collection)
        ];
    }
}
