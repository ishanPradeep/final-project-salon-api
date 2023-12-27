<?php

namespace App\Repositories\Salon;

use App\Helpers\Helper;
use App\Http\Resources\Salon\ServiceCollection;
use App\Http\Resources\Salon\ServiceResource;
use App\Models\Salon\SalonType\SalonType;
use App\Models\Salon\Service\Service;
use App\Repositories\Salon\Interface\ServiceRepositoryInterface;
use Illuminate\Http\Response;

class ServiceRepository implements ServiceRepositoryInterface
{
    public function all($request)
    {

        if($request->input('all', '') == 1) {
            $service_list = Service::all();
        } else {
            $service_list = Service::orderBy('created_at', 'desc')->paginate(10);
        }

        if (count($service_list) > 0) {
            return new ServiceCollection($service_list);
        } else {
            return Helper::success(Response::$statusTexts[Response::HTTP_NO_CONTENT], Response::HTTP_NO_CONTENT);
        }
    }
    public function findById($id)
    {

        $service = Service::find($id);

        if ($service) {
            return new ServiceResource($service);
        } else {
            return Helper::success(Response::$statusTexts[Response::HTTP_NO_CONTENT], Response::HTTP_NO_CONTENT);
        }
    }

    public function store($request)
    {
        $service = new Service();

        $service->name = $request->name;
        $service->salon_type_id = $request->salon_type_id;
        $service->icon = $request->icon;

        if ($service->save()) {
            activity('service')
                ->performedOn($service)
                ->causedBy(auth()->user())
                ->withProperties(['name' => $service->name])
                ->log('created');

            return new ServiceResource($service);
        } else {
            return Helper::error(Response::$statusTexts[Response::HTTP_NO_CONTENT], Response::HTTP_NO_CONTENT);
        }
    }
}
