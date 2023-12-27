<?php

namespace App\Repositories\Review;

use App\Helpers\Helper;
use App\Http\Resources\Employer\EmployerResource;
use App\Models\Employer\Employer;
use App\Models\Review\SalonReview;
use App\Models\User\User;
use App\Models\User\UserLevel;
use App\Repositories\Review\Interface\SalonReviewRepositoryInterface;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;

class SalonReviewRepository implements SalonReviewRepositoryInterface
{

    public function store($request)
    {
        $review = new SalonReview();
        $review->review = $request->review;
        $review->rating = $request->rating;
        $review->salon_id = $request->salon_id;
        $review->user_id = auth()->user()->id;


        if ($review->save()) {

            activity('salonReview')
                ->performedOn($review)
                ->causedBy(auth()->user())
                ->withProperties(['name' => $review->salon->name])
                ->log('created');

            return Helper::success(Response::$statusTexts[Response::HTTP_OK], Response::HTTP_OK);
        } else {
            return Helper::error(Response::$statusTexts[Response::HTTP_NO_CONTENT], Response::HTTP_NO_CONTENT);
        }
    }

    public function update($request)
    {
        $review = SalonReview::find($request->id);
        $review->review = $request->review;
        $review->rating = $request->rating;

        if ($review->save()) {

            activity('salonReview')
                ->performedOn($review)
                ->causedBy(auth()->user())
                ->withProperties(['name' => $review->salon->name])
                ->log('updated');

            return Helper::success(Response::$statusTexts[Response::HTTP_OK], Response::HTTP_OK);
        } else {
            return Helper::error(Response::$statusTexts[Response::HTTP_NO_CONTENT], Response::HTTP_NO_CONTENT);
        }
    }

    public function deleteSalonReview($id)
    {
        $review = SalonReview::find($id);
        $temp = $review;
        if ($review) {

            if($review->delete()){

                activity('salonReview')
                    ->performedOn($temp)
                    ->causedBy(auth()->user())
                    ->withProperties(['name' => $temp->salon->name])
                    ->log('deleted');

                return Helper::success(Response::$statusTexts[Response::HTTP_OK], Response::HTTP_OK);

            }else{
                return Helper::error(Response::$statusTexts[Response::HTTP_NO_CONTENT], Response::HTTP_NO_CONTENT);
            }
        } else {
            return Helper::error(Response::$statusTexts[Response::HTTP_NO_CONTENT], Response::HTTP_NO_CONTENT);
        }
    }



}
