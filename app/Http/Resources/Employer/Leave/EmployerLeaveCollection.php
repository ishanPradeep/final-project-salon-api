<?php

namespace App\Http\Resources\Employer\Leave;

use App\Http\Resources\Employer\EmployerResource;
use Illuminate\Http\Resources\Json\ResourceCollection;

class EmployerLeaveCollection extends ResourceCollection
{
    public static $wrap = 'leave_list';

    /**
     * Transform the resource collection into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        return [
            'leave_list'=> EmployerLeaveResource::collection($this->collection)
        ];
    }

}
