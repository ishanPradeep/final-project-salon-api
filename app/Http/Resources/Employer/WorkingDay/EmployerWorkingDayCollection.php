<?php

namespace App\Http\Resources\Employer\WorkingDay;

use App\Http\Resources\Employer\EmployerResource;
use Illuminate\Http\Resources\Json\ResourceCollection;

class EmployerWorkingDayCollection extends ResourceCollection
{
    public static $wrap = 'employer_working_day_list';

    /**
     * Transform the resource collection into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        return [
            'employer_working_day_list'=> EmployerWorkingDayResource::collection($this->collection)
        ];
    }

}
