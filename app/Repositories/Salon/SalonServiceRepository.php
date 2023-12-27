<?php

namespace App\Repositories\Salon;

use App\Helpers\Helper;
use App\Http\Resources\Employer\EmployerSalonServiceResource;
use App\Http\Resources\Salon\SalonServiceCollection;
use App\Http\Resources\Salon\SalonServiceResource;
use App\Models\Booking\Booking;
use App\Models\Employer\EmployerSalonService;
use App\Models\Salon\SalonService\BannerImage\SalonServiceBannerImage;
use App\Models\Salon\SalonService\SalonService;
use App\Models\Salon\SalonService\SalonSubService\SalonSubService;
use App\Repositories\Salon\Interface\SalonServiceRepositoryInterface;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Storage;

class SalonServiceRepository implements SalonServiceRepositoryInterface
{

    public function all($request)
    {
        $userLatitude = $request->user_latitude;
        $userLongitude = $request->user_longitude;

        $query = SalonService::query();
        $query->select('salon_services.*')
            ->join('salons', 'salon_services.salon_id', '=', 'salons.id')
            ->orderByRaw(
                "ABS(salons.latitude - $userLatitude) + ABS(salons.longitude - $userLongitude) ASC"
            );

        if ($request->service != null) {
            $query->whereHas('service', function ($q) use ($request) {
                $q->where('id', $request->service);
            });
        }

        if ($request->search != null) {
            $query->whereHas('salon', function ($q) use ($request) {
                $q->where('name', 'like', '%' . $request->search . '%');
            });
        }

        if ($request->salon_type != null) {
            $query->whereHas('salon.salonType', function ($query) use ($request) {
                $query->where('id', $request->salon_type);
            });
        }

        if($request->input('all', '') == 1) {
            $salon_service_list = $query->get();
        } else {
            $salon_service_list = Helper::paginate($query->get());
        }

        $salon_service_list = $salon_service_list->filter(function ($service) {
            return $service->salonSubService->isNotEmpty();
        });

        if (count($salon_service_list) > 0) {
            return new SalonServiceCollection($salon_service_list);
        } else {
            return Helper::error(Response::$statusTexts[Response::HTTP_NO_CONTENT], Response::HTTP_NO_CONTENT);
        }
    }

    public function findById($id)
    {

        $salon_service = SalonService::find($id);

        if ($salon_service) {
            return new SalonServiceResource($salon_service);
        } else {
            return Helper::success(Response::$statusTexts[Response::HTTP_NO_CONTENT], Response::HTTP_NO_CONTENT);
        }
    }

    public function findBySalonId($salon_id)
    {

        $salon_services = SalonService::where('salon_id',$salon_id)->get();

        if (count($salon_services) > 0) {
            return new SalonServiceCollection($salon_services);
        } else {
            return Helper::success(Response::$statusTexts[Response::HTTP_NO_CONTENT], Response::HTTP_NO_CONTENT);
        }
    }

    public function store($request)
    {
        $isServiceIdCheck = SalonService::where('salon_id',$request->salon_id)->where('service_id',$request->service_id)->first();
        if($isServiceIdCheck){
            return Helper::error("ServiceSeeder already exists", Response::HTTP_ALREADY_REPORTED);
        }
        $salon_service = new SalonService();
        $salon_service->salon_service_name = $request->salon_service_name;
        $salon_service->salon_id = $request->salon_id;
        $salon_service->service_id = $request->service_id;

        if ($salon_service->save()) {

            if ($request->has('banner_images')){
                foreach ($request->file('banner_images') as $key => $imagefile) {
                    $banner_image = new SalonServiceBannerImage();
                    $banner_file_name = 'salon/salon-service/banner/' . uniqid() . '.' . $imagefile->getClientOriginalExtension();
                    Storage::disk('s3')->put($banner_file_name, file_get_contents($imagefile));
                    $banner_image->path = $banner_file_name;
                    $banner_image->salon_service_id = $salon_service->id;
                    $banner_image->save();
                }
            }

            activity('salon_service')
                ->performedOn($salon_service)
                ->causedBy(auth()->user())
                ->withProperties(['name' => $salon_service->name])
                ->log('created');

            return new SalonServiceResource($salon_service);
        } else {
            return Helper::error(Response::$statusTexts[Response::HTTP_NO_CONTENT], Response::HTTP_NO_CONTENT);
        }
    }
    public function update($request)
    {
        $salon_service = SalonService::find($request->salon_service_id);
        $salon_service->salon_service_name = $request->salon_service_name;
        $salon_service->salon_id = $request->salon_id;
        $salon_service->service_id = $request->service_id;

        if ($request->file('banner_images')){
            foreach ($request->file('banner_images') as $key => $imagefile) {
                $banner_image = new SalonServiceBannerImage();
                $banner_file_name = 'salon/salon-service/banner/' . uniqid() . '.' . $imagefile->getClientOriginalExtension();
                Storage::disk('s3')->put($banner_file_name, file_get_contents($imagefile));
                $banner_image->path = $banner_file_name;
                $banner_image->salon_service_id = $salon_service->id;
                $banner_image->save();
            }
        }

        if ($salon_service->save()) {
            activity('salon_service')
                ->performedOn($salon_service)
                ->causedBy(auth()->user())
                ->withProperties(['name' => $salon_service->name])
                ->log('updated');

            return new SalonServiceResource($salon_service);
        } else {
            return Helper::error(Response::$statusTexts[Response::HTTP_NO_CONTENT], Response::HTTP_NO_CONTENT);
        }
    }

    public function deleteSalonServiceBannerImage($id){
        $salonServiceBannerImage = SalonServiceBannerImage::find($id);
        if($salonServiceBannerImage){
            $salon_service = SalonService::find($salonServiceBannerImage->salon_service_id);
            if ($salonServiceBannerImage) {
                $disk = Storage::disk('s3');
                if ($disk->exists($salonServiceBannerImage->path)) {
                    $disk->delete($salonServiceBannerImage->path);
                    $salonServiceBannerImage->delete();
                }
            }
            return new SalonServiceResource($salon_service);
        }else {
            return Helper::error(Response::$statusTexts[Response::HTTP_NO_CONTENT], Response::HTTP_NO_CONTENT);
        }
    }
    public function deleteSalonService($id){
        $salonService = SalonService::find($id);
        $currentDate = date('Y-m-d');

        if($salonService){

            $salonSubServices = SalonSubService::where('salon_service_id',$id)->get();
            $booking =Booking::whereIn('salon_sub_service_id',$salonSubServices->pluck('id'))
                ->where('date', '>=', $currentDate)
                ->where('status', '!=', "rejected")
                ->first();
            if($booking !== null && $booking->status != 'rejected' ){
                return Helper::error("Already have the reservation", Response::HTTP_IM_USED);
            }

            foreach ($salonSubServices as $salonSubService) {
                EmployerSalonService::where('salon_sub_service_id',$salonSubService->id)->delete();

                $disk = Storage::disk('s3');
                if ($disk->exists($salonSubService->image)) {
                    $disk->delete($salonSubService->image);
                    $salonSubService->delete();
                }
            }

            foreach ($salonService->bannerImages as $bannerImage) {
                $disk = Storage::disk('s3');
                if ($disk->exists($bannerImage->path)) {
                    $disk->delete($bannerImage->path);
                    $bannerImage->delete();
                }
            }

            $salonService->delete();
            return Helper::success(Response::$statusTexts[Response::HTTP_OK], Response::HTTP_OK);
        }else{
            return Helper::error(Response::$statusTexts[Response::HTTP_NO_CONTENT], Response::HTTP_NO_CONTENT);
        }
    }

}
