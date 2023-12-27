<?php

namespace App\Http\Resources\Employer;

use Illuminate\Http\Resources\Json\ResourceCollection;

class EmployerCollection extends ResourceCollection
{
    public static $wrap = 'employer_list';

    /**
     * Transform the resource collection into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        return [
            'employer_list'=> EmployerResource::collection($this->collection)
        ];
    }

}
