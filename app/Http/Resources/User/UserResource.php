<?php

namespace App\Http\Resources\User;

use App\Helpers\Helper;
use App\Models\Employer\Employer;
use Illuminate\Http\Resources\Json\JsonResource;

class UserResource extends JsonResource
{
    public static $wrap = 'user';

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
            'name'=> $this->fname.' '.$this->lname,
            'fname'=>$this->fname,
            'lname'=>$this->lname,
            'address'=>$this->address,
            'latitude'=>$this->latitude,
            'longitude'=>$this->longitude,
            'contact_no'=>$this->contact_no,
            'email'=> $this->email,
            'have_salon'=> $this->have_salon,
            'contact_number'=> $this->contact_no,
            'profile_photo'=>Helper::singleImageResponse($this->profile_photo),
            'user_level'=>$this->userLevel->scope,
//           'roles'=> $this->getRoleNames(),
            'employer'=> $this->employer()
        ];
    }
    public function employer(){
        if($this->userLevel->scope == 'employer'){
            return Employer::where('user_id',$this->id)->first();
        }
    }
}
