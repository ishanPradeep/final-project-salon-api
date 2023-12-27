<?php

namespace App\Http\Resources\Salon;

use App\Helpers\Helper;
use App\Models\Salon\SalonType\SalonType;
use Illuminate\Http\Resources\Json\JsonResource;

class SalonResource extends JsonResource
{
    public static $wrap = 'salon';

    /**
     * Transform the resource into an array.
     *
     * @param \Illuminate\Http\Request $request
     * @return array
     */
    public function toArray($request)
    {
        $this->salonOpenEndTimes();
        return [
            'id' => $this->id,
            'userId' => $this->user_id,
            'salonType' => SalonType::find($this->salon_type_id),
            'name' => $this->name,
            'description' => $this->description,
            'email' => $this->email,
            'contact_number' => $this->contact_number,
            'address' => $this->address,
            'latitude' => $this->latitude,
            'longitude' => $this->longitude,
            'publish' => $this->publish,
            'salonLogo' => Helper::singleImageResponse($this->salon_logo),
            'salonBannerImages' => Helper::imageResponse(['data' => $this->salonBannerImages()->get()]),
            'placeOffers' => $this->placeOffers,
            'weekday' => $this->salonOpenEndTimes->where('day_id', 2)->first(),
            'weekend' => $this->salonOpenEndTimes->where('day_id', 1)->first(),
            'salon_reviews' => $this->salonReview()

        ];
    }

    public function salonReview()
    {
        $final_review = [];
        foreach ($this->salonReviews as $salonReview) {
            $review =
                [
                    'id' => $salonReview->id,
                    'review' => $salonReview->review,
                    'rating_avg' => Helper::getCalculateAverageSalonRating($salonReview->salon_id),
                    'rating_count' => $salonReview->rating,
                    'user_id' => $salonReview->user_id,
                    'user_name' => $salonReview->user->fname . ' ' . $salonReview->user->lname,
                    'user_photo' => Helper::singleImageResponse($salonReview->user->profile_photo),
                    'salon_id' => $salonReview->salon_id,
                    "created_at" => $salonReview->created_at,
                    "updated_at" => $salonReview->updated_at

                ];
            array_push($final_review, $review);
        }
        return $final_review;
    }
}
