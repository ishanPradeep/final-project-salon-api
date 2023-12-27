<?php

namespace App\Http\Resources\Salon;

use App\Helpers\Helper;
use App\Models\Salon\Salon;
use App\Models\Salon\SalonType\SalonType;
use Illuminate\Http\Resources\Json\JsonResource;

class SalonServiceResource extends JsonResource
{
    public static $wrap = 'salon_service';

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
            'salon_service_name' => $this->salon_service_name,
            'salon_id'=> $this->salon_id,
            'salon' => Salon::find($this->salon_id),
            'salon_logo'=>Helper::singleImageResponse(Salon::find($this->salon_id)->salon_logo),
            'service_id'=> $this->service_id,
            'service' => SalonType::find($this->service_id),
            'user' => $this->salon->user,
            'banner_images'=> Helper::imageResponse(['data' => $this->bannerImages()->get()]),
            'salon_banner_images'=> Helper::imageResponse(['data' => $this->salon->salonBannerImages()->get()]),
            'salon_reviews' => $this->salonReview()

        ];
    }

    public function salonReview(){
        $final_review = [];
        foreach ($this->salon->salonReviews as $salonReview) {
            $review =
                [
                    'id'=> $salonReview->id,
                    'review'=> $salonReview->review,
                    'rating_avg'=> Helper::getCalculateAverageSalonRating($salonReview->salon_id),
                    'rating_count'=> $salonReview->rating,
                    'user_id'=> $salonReview->user_id,
                    'user_name'=> $salonReview->user->fname. ' ' .$salonReview->user->lname,
                    'user_photo'=>Helper::singleImageResponse($salonReview->user->profile_photo),
                    'salon_id'=> $salonReview->salon_id,
                    "created_at"=> $salonReview->created_at,
                    "updated_at"=> $salonReview->updated_at

            ];
            array_push($final_review,$review);
        }
        return $final_review;
    }
}
