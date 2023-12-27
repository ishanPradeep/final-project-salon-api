<?php

namespace App\Repositories\Salon;

use App\Helpers\Helper;
use App\Http\Resources\Employer\EmployerCollection;
use App\Http\Resources\Salon\SalonSubServiceCollection;
use App\Http\Resources\Salon\SalonSubServiceResource;
use App\Models\Booking\Booking;
use App\Models\Employer\Employer;
use App\Models\Salon\SalonService\SalonService;
use App\Models\Salon\SalonService\SalonSubService\SalonSubService;
use App\Repositories\Salon\Interface\SalonSubServiceRepositoryInterface;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Storage;

class SalonSubServiceRepository implements SalonSubServiceRepositoryInterface
{
    public function findById($id)
    {

        $salon_sub_service = SalonSubService::find($id);

        if ($salon_sub_service) {
            return new SalonSubServiceResource($salon_sub_service);
        } else {
            return Helper::success(Response::$statusTexts[Response::HTTP_NO_CONTENT], Response::HTTP_NO_CONTENT);
        }
    }

    public function findBySalonServiceId($salon_service_id)
    {

        $salon_sub_services = SalonSubService::where('salon_service_id',$salon_service_id)->get();

        if (count($salon_sub_services) > 0) {
            return new SalonSubServiceCollection($salon_sub_services);
        } else {
            return Helper::success(Response::$statusTexts[Response::HTTP_NO_CONTENT], Response::HTTP_NO_CONTENT);
        }
    }

    public function findByName($request)
    {

        $query = SalonSubService::query();

        if ($request->search != null) {
            $query->where('sub_service_name', 'like', '%' . $request->search . '%');
        }
        $query->where('salon_service_id',$request->salon_service_id);

        if($request->input('all', '') == 1) {
            $salon_sub_services = $query->get();
        } else {
            $salon_sub_services = Helper::paginate($query->get());
        }

        if (count($salon_sub_services) > 0) {
            return new SalonSubServiceCollection($salon_sub_services);
        } else {
            return Helper::success(Response::$statusTexts[Response::HTTP_NO_CONTENT], Response::HTTP_NO_CONTENT);
        }
    }

    public function store($request)
    {

        $salon_sub_service = new SalonSubService();
        $salon_sub_service->sub_service_name = $request->sub_service_name;
        $salon_sub_service->salon_service_id = $request->salon_service_id;
        $salon_sub_service->hour = $request->hour;
        $salon_sub_service->status = $request->status;
        $salon_sub_service->auto_approval = $request->auto_approval;
        $salon_sub_service->booking_cancellation = $request->booking_cancellation;
        if ($request->file('image')){
            $image = $request->file('image');
            $filename = 'salon/salon-sub-service/banner/' . uniqid() . '.' . $image->getClientOriginalExtension();
            Storage::disk('s3')->put($filename, file_get_contents($image));
            $salon_sub_service->image = $filename;
        }
        if ($salon_sub_service->save()) {
            activity('salon_sub_service')
                ->performedOn($salon_sub_service)
                ->causedBy(auth()->user())
                ->withProperties(['name' => $salon_sub_service->sub_service_name])
                ->log('created');

            return new SalonSubServiceResource($salon_sub_service);
        } else {
            return Helper::error(Response::$statusTexts[Response::HTTP_NO_CONTENT], Response::HTTP_NO_CONTENT);
        }
    }
    public function update($request)
    {
        $salon_sub_service = SalonSubService::find($request->salon_sub_service_id);
        if ($request->file('image')) {
            $disk = Storage::disk('s3');
            if ($disk->exists($salon_sub_service->image)) {
                $disk->delete($salon_sub_service->image);
            }

            $image = $request->file('image');
            $filename = 'salon/salon-sub-service/banner/' . uniqid() . '.' . $image->getClientOriginalExtension();
            $disk->put($filename, file_get_contents($image));
            $salon_sub_service->image = $filename;
        }

        $salon_sub_service->sub_service_name = $request->sub_service_name;
        $salon_sub_service->salon_service_id = $request->salon_service_id;
        $salon_sub_service->hour = $request->hour;
        $salon_sub_service->status = $request->status;
        $salon_sub_service->auto_approval = $request->auto_approval;
        $salon_sub_service->booking_cancellation = $request->booking_cancellation;


        if ($salon_sub_service->save()) {
            activity('salon_sub_service')
                ->performedOn($salon_sub_service)
                ->causedBy(auth()->user())
                ->withProperties(['name' => $salon_sub_service->sub_service_name])
                ->log('updated');

            return new SalonSubServiceResource($salon_sub_service);
        } else {
            return Helper::error(Response::$statusTexts[Response::HTTP_NO_CONTENT], Response::HTTP_NO_CONTENT);
        }
    }

    public function deleteSalonSubService($id){

        $salonsubService = SalonSubService::find($id);

        $currentDate = date('Y-m-d');

        $booking = Booking::where('salon_sub_service_id',$salonsubService->id)
            ->where('date', '>=', $currentDate)
            ->where('status', '!=', "rejected")
            ->first();
        if($booking !== null && $booking->status != 'rejected' ){
            return Helper::error("Already have the reservation", Response::HTTP_IM_USED);
        }


        if($salonsubService){
            $disk = Storage::disk('s3');
            if ($disk->exists($salonsubService->image)) {
                $disk->delete($salonsubService->image);
                $salonsubService->delete();
            }
            $salonsubService->delete();
            return Helper::success(Response::$statusTexts[Response::HTTP_OK], Response::HTTP_OK);
        }else{
            return Helper::error(Response::$statusTexts[Response::HTTP_NO_CONTENT], Response::HTTP_NO_CONTENT);
        }
    }
}
