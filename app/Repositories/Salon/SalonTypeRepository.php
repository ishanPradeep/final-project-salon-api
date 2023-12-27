<?php

namespace App\Repositories\Salon;

use App\Helpers\Helper;
use App\Http\Resources\Salon\SalonTypeCollection;
use App\Http\Resources\Salon\SalonTypeResource;
use App\Models\Salon\SalonType\SalonType;
use App\Repositories\Salon\Interface\SalonTypeRepositoryInterface;
use Illuminate\Http\Response;

class SalonTypeRepository implements SalonTypeRepositoryInterface
{
    public function all($request)
    {

        if($request->input('all', '') == 1) {
            $salon_type_list = SalonType::all();
        } else {
            $salon_type_list = SalonType::orderBy('created_at', 'desc')->paginate(10);
        }

        if (count($salon_type_list) > 0) {
            return new SalonTypeCollection($salon_type_list);
        } else {
            return Helper::success(Response::$statusTexts[Response::HTTP_NO_CONTENT], Response::HTTP_NO_CONTENT);
        }
    }
    public function findById($id)
    {

        $salon_type = SalonType::find($id);

        if ($salon_type) {
            return new SalonTypeResource($salon_type);
        } else {
            return Helper::success(Response::$statusTexts[Response::HTTP_NO_CONTENT], Response::HTTP_NO_CONTENT);
        }
    }

    public function store($request)
    {
        $salon_type = new SalonType();

        $salon_type->title = $request->title;
        $salon_type->subtitle = $request->subtitle;
        $salon_type->icon = $request->icon;

        if ($salon_type->save()) {
            activity('salon_type')
                ->performedOn($salon_type)
                ->causedBy(auth()->user())
                ->withProperties(['name' => $salon_type->title])
                ->log('created');

            return new SalonTypeResource($salon_type);
        } else {
            return Helper::error(Response::$statusTexts[Response::HTTP_NO_CONTENT], Response::HTTP_NO_CONTENT);
        }
    }
}
