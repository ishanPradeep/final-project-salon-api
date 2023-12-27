<?php

namespace App\Http\Resources\Employer;

use App\Helpers\Helper;
use App\Http\Resources\Employer\Leave\EmployerLeaveResource;
use App\Http\Resources\Employer\WorkingDay\EmployerWorkingDayResource;
use Illuminate\Http\Resources\Json\JsonResource;

class EmployerResource extends JsonResource
{
    public static $wrap = 'employer';

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
            'user_id'=> $this->user_id,
            'qualifications'=> $this->qualifications,
            'name'=> $this->user->fname.' '.$this->user->lname,
            'email'=> $this->user->email,
            'contact_number'=> $this->user->contact_no,
            'address'=> $this->user->address,
            'latitude'=> $this->user->latitude,
            'longitude'=> $this->user->longitude,
            'profile_photo'=>Helper::singleImageResponse($this->user->profile_photo),
            'publish'=> $this->publish,
            'employer_leaves' => EmployerLeaveResource::collection($this->employer_leaves),
            'employer_working_days' => EmployerWorkingDayResource::collection($this->employer_working_day),
            'employer_reviews' => $this->employerReview()

        ];
    }

    public function employerReview()
    {
        $final_review = [];
        foreach ($this->employerReviews as $employerReview) {
            $review =
                [
                    'id' => $employerReview->id,
                    'review' => $employerReview->review,
                    'rating_avg' => Helper::getCalculateAverageEmployerRating($employerReview->employer_id),
                    'rating_count' => $employerReview->rating,
                    'user_id' => $employerReview->user_id,
                    'user_name' => $employerReview->user->fname . ' ' . $employerReview->user->lname,
                    'user_photo' => Helper::singleImageResponse($employerReview->user->profile_photo),
                    'employer_id' => $employerReview->employer_id,
                    "created_at" => $employerReview->created_at,
                    "updated_at" => $employerReview->updated_at

                ];
            array_push($final_review, $review);
        }
        return $final_review;
    }

}
