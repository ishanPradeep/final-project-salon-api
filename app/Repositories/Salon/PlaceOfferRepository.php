<?php

namespace App\Repositories\Salon;

use App\Helpers\Helper;
use App\Http\Resources\Salon\PlaceOfferCollection;
use App\Http\Resources\Salon\PlaceOfferResource;
use App\Http\Resources\Salon\SalonServiceCollection;
use App\Http\Resources\Salon\SalonServiceResource;
use App\Models\Salon\PlaceOffer\PlaceOffer;
use App\Models\Salon\SalonService\SalonService;
use App\Repositories\Salon\Interface\PlaceOfferRepositoryInterface;
use Illuminate\Http\Response;

class PlaceOfferRepository implements PlaceOfferRepositoryInterface
{

    public function all($request)
    {

        if($request->input('all', '') == 1) {
            $place_offer_list = PlaceOffer::all();
        } else {
            $place_offer_list = PlaceOffer::orderBy('created_at', 'desc')->paginate(10);
        }

        if (count($place_offer_list) > 0) {
            return new PlaceOfferCollection($place_offer_list);
        } else {
            return Helper::success(Response::$statusTexts[Response::HTTP_NO_CONTENT], Response::HTTP_NO_CONTENT);
        }
    }
    public function findById($id)
    {

        $place_offer = PlaceOffer::find($id);

        if ($place_offer) {
            return new PlaceOfferResource($place_offer);
        } else {
            return Helper::success(Response::$statusTexts[Response::HTTP_NO_CONTENT], Response::HTTP_NO_CONTENT);
        }
    }
    public function store($request)
    {
        $place_offer = new PlaceOffer();
        $place_offer->name = $request->name;
        $place_offer->icon = $request->icon;


        if ($place_offer->save()) {
            activity('place_offer')
                ->performedOn($place_offer)
                ->causedBy(auth()->user())
                ->withProperties(['name' => $place_offer->name])
                ->log('created');

            return new PlaceOfferResource($place_offer);
        } else {
            return Helper::error(Response::$statusTexts[Response::HTTP_NO_CONTENT], Response::HTTP_NO_CONTENT);
        }
    }
}
