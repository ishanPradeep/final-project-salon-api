<?php

namespace App\Repositories\Review;
use App\Helpers\Helper;
use App\Models\Review\EmployerReview;
use App\Repositories\Review\Interface\EmployerReviewRepositoryInterface;
use Illuminate\Http\Response;

class EmployerReviewRepository implements EmployerReviewRepositoryInterface
{
    public function store($request)
    {
        $review = new EmployerReview();
        $review->review = $request->review;
        $review->rating = $request->rating;
        $review->employer_id = $request->employer_id;
        $review->user_id = auth()->user()->id;


        if ($review->save()) {

            activity('employerReview')
                ->performedOn($review)
                ->causedBy(auth()->user())
                ->withProperties(['name' => $review->employer->user->fname])
                ->log('created');

            return Helper::success(Response::$statusTexts[Response::HTTP_OK], Response::HTTP_OK);
        } else {
            return Helper::error(Response::$statusTexts[Response::HTTP_NO_CONTENT], Response::HTTP_NO_CONTENT);
        }
    }

    public function update($request)
    {
        $review = EmployerReview::find($request->id);
        $review->review = $request->review;
        $review->rating = $request->rating;

        if ($review->save()) {

            activity('employerReview')
                ->performedOn($review)
                ->causedBy(auth()->user())
                ->withProperties(['name' => $review->employer->user->fname])
                ->log('updated');

            return Helper::success(Response::$statusTexts[Response::HTTP_OK], Response::HTTP_OK);
        } else {
            return Helper::error(Response::$statusTexts[Response::HTTP_NO_CONTENT], Response::HTTP_NO_CONTENT);
        }
    }

    public function deleteEmployerReview($id)
    {
        $review = EmployerReview::find($id);
        $temp = $review;
        if ($review) {

            if($review->delete()){

                activity('employerReview')
                    ->performedOn($temp)
                    ->causedBy(auth()->user())
                    ->withProperties(['name' => $temp->employer->user->fname])
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
