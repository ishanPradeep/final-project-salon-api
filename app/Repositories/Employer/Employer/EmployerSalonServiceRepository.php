<?php

namespace App\Repositories\Employer\Employer;

use App\Helpers\Helper;
use App\Http\Resources\Employer\EmployerSalonServiceDetailsBySalonServiceIdCollection;
use App\Http\Resources\Employer\AllSalonServiceDetailsByEmployerIdCollection;
use App\Http\Resources\Employer\EmployerCollection;
use App\Http\Resources\Employer\EmployerResource;
use App\Http\Resources\Employer\EmployerSalonServiceCollection;
use App\Http\Resources\Employer\EmployerSalonServiceResource;
use App\Models\Employer\Employer;
use App\Models\Employer\EmployerSalonService;
use App\Models\Employer\WorkingDay\EmployerWorkingDay;
use App\Models\Salon\SalonService\SalonService;
use App\Models\User\User;
use App\Models\User\UserLevel;
use App\Repositories\Employer\Employer\Interface\EmployerSalonServiceRepositoryInterface;
use Illuminate\Http\Response;

class EmployerSalonServiceRepository implements EmployerSalonServiceRepositoryInterface
{
    public function all($request)
    {
        if($request->input('all', '') == 1) {
            $employer_list = EmployerSalonService::all();
        } else {
            $employer_list = EmployerSalonService::orderBy('created_at', 'desc')->paginate(10);
        }

        if (count($employer_list) > 0) {
            return new EmployerSalonServiceCollection($employer_list);
        } else {
            return Helper::success(Response::$statusTexts[Response::HTTP_NO_CONTENT], Response::HTTP_NO_CONTENT);
        }
    }

    public function allSalonSubServiceByEmployerId($employer_id)
    {
        $list = EmployerSalonService::where('employer_id',$employer_id)->get();

        if (count($list) > 0) {
            return new AllSalonServiceDetailsByEmployerIdCollection($list);
        } else {
            return Helper::success(Response::$statusTexts[Response::HTTP_NO_CONTENT], Response::HTTP_NO_CONTENT);
        }
    }
    public function allEmployerBySalonSubServiceId($salon_sub_service_id)
    {
        $list = EmployerSalonService::where('salon_sub_service_id',$salon_sub_service_id)->get();
        if (count($list) > 0) {
            return new EmployerSalonServiceDetailsBySalonServiceIdCollection($list);
        } else {
            return Helper::success(Response::$statusTexts[Response::HTTP_NO_CONTENT], Response::HTTP_NO_CONTENT);
        }
    }
    public function findById($id)
    {

        $employer = EmployerSalonService::find($id);

        if ($employer) {
            return new EmployerSalonServiceResource($employer);
        } else {
            return Helper::success(Response::$statusTexts[Response::HTTP_NO_CONTENT], Response::HTTP_NO_CONTENT);
        }
    }
    public function store($request)
    {
        $hasEmployerSalonService = EmployerSalonService::where('employer_id',$request->employer_id)->
                        where('salon_sub_service_id',$request->salon_sub_service_id)->first();
        if($hasEmployerSalonService){
            return Helper::error("Already exist",Response::HTTP_ALREADY_REPORTED);
        }

        $employerWorkingDay = EmployerWorkingDay::where('employer_id',$request->employer_id)->first();
        if($employerWorkingDay == null){
            return Helper::error("Employer's working days are not added",Response::HTTP_NOT_IMPLEMENTED);
        }

        $employerSalonService = new EmployerSalonService();
        $employerSalonService->employer_id = $request->employer_id;
        $employerSalonService->salon_sub_service_id = $request->salon_sub_service_id;
        $employerSalonService->price = $request->price;
        $employerSalonService->buffer_time = $request->buffer_time;
        if ($employerSalonService->save()) {

            activity('employer_salon_service')
                ->performedOn($employerSalonService)
                ->causedBy(auth()->user())
                ->withProperties(['name' => "employer salon service"])
                ->log('created');

            return new EmployerSalonServiceResource($employerSalonService);
        } else {
            return Helper::error(Response::$statusTexts[Response::HTTP_NO_CONTENT], Response::HTTP_NO_CONTENT);
        }
    }

    public function deleteEmployerFromEmployerSalonService($employer_salon_service_id)
    {

        $employer = EmployerSalonService::find($employer_salon_service_id);
        return Helper::success(Response::$statusTexts[Response::HTTP_NOT_IMPLEMENTED], Response::HTTP_NOT_IMPLEMENTED);
        if ($employer) {
            return new EmployerSalonServiceResource($employer);
        } else {
            return Helper::success(Response::$statusTexts[Response::HTTP_NO_CONTENT], Response::HTTP_NO_CONTENT);
        }
    }
}
