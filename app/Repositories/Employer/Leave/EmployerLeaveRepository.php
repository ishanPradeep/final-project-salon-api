<?php

namespace App\Repositories\Employer\Leave;

use App\Helpers\Helper;
use App\Http\Resources\Employer\EmployerResource;
use App\Http\Resources\Employer\Leave\EmployerLeaveCollection;
use App\Http\Resources\Employer\Leave\EmployerLeaveResource;
use App\Models\Booking\Booking;
use App\Models\Employer\Employer;
use App\Models\Employer\Leave\EmployerLeave;
use App\Models\Salon\Salon;
use App\Repositories\Employer\Leave\Interface\EmployerLeaveRepositoryInterface;
use Illuminate\Http\Response;

class EmployerLeaveRepository implements EmployerLeaveRepositoryInterface
{
    public function all($request)
    {

        if($request->input('all', '') == 1) {
            $leave_list = EmployerLeave::all();
        } else {
            $leave_list = EmployerLeave::orderBy('created_at', 'desc')->paginate(10);
        }

        if (count($leave_list) > 0) {
            return new EmployerLeaveCollection($leave_list);
        } else {
            return Helper::success(Response::$statusTexts[Response::HTTP_NO_CONTENT], Response::HTTP_NO_CONTENT);
        }
    }

    public function getAllLeavesByEmployerId($employerId)
    {

        $leave_list = EmployerLeave::where('employer_id',$employerId)->get();

        if (count($leave_list) > 0) {
            return new EmployerLeaveCollection($leave_list);
        } else {
            return Helper::success(Response::$statusTexts[Response::HTTP_NO_CONTENT], Response::HTTP_NO_CONTENT);
        }
    }
    public function getAllLeavesBySalonId($salonId)
    {
        $employerLeaves = EmployerLeave::whereHas('employer', function ($query) use ($salonId) {
            $query->where('salon_id', $salonId);
        })->get();

        if (count($employerLeaves) > 0) {
            return new EmployerLeaveCollection($employerLeaves);
        } else {
            return Helper::success(Response::$statusTexts[Response::HTTP_NO_CONTENT], Response::HTTP_NO_CONTENT);
        }
    }

    public function findById($id)
    {

        $leave = EmployerLeave::find($id);

        if ($leave) {
            return new EmployerLeaveResource($leave);
        } else {
            return Helper::success(Response::$statusTexts[Response::HTTP_NO_CONTENT], Response::HTTP_NO_CONTENT);
        }
    }

    public function delete($id)
    {

        $leave = EmployerLeave::find($id);
        if ($leave) {
            $leave->delete();
            return Helper::success(Response::$statusTexts[Response::HTTP_OK], Response::HTTP_OK);
        } else {
            return Helper::success(Response::$statusTexts[Response::HTTP_NO_CONTENT], Response::HTTP_NO_CONTENT);
        }
    }

    public function leaveManage($request)
    {
        $employerLeave = EmployerLeave::find($request->leave_id);
        if($employerLeave){

            $booking = Booking::where('employer_id', $employerLeave->employer_id)
                ->where('date', $employerLeave->date)->where(function($query) use ($employerLeave) {
                    $query->where(function($query) use ($employerLeave) {
                        $query->where('from_time', '<', $employerLeave->to_time)
                            ->where('to_time', '>', $employerLeave->from_time);
                    });
                })
                ->first();

            if($booking !== null && $booking->status != 'rejected' && $request->status == true){
                return Helper::error("Already have the reservation", Response::HTTP_IM_USED);
            }

            $employerLeave->status = $request->status;
            $employerLeave->save();
            return new EmployerLeaveResource($employerLeave);
        }else {
            return Helper::success(Response::$statusTexts[Response::HTTP_NO_CONTENT], Response::HTTP_NO_CONTENT);
        }
    }


    public function store($request)
    {
        if (!Employer::find($request->employer_id)){
            return Helper::error("Employee does not exist", Response::HTTP_NOT_ACCEPTABLE);
        }
        $from_time = $request->half_day_leave == "enabled" ? $request->from_time : "00:00";
        $to_time = $request->half_day_leave == "enabled" ? $request->to_time : "23:59";

        $booking = Booking::where('employer_id', $request['employer_id'])
            ->where('date', $request['date'])->where(function($query) use ($from_time, $to_time) {
                $query->where(function($query) use ($from_time, $to_time) {
                    $query->where('from_time', '<', $to_time)
                        ->where('to_time', '>', $from_time);
                });
            })
            ->first();
        if($booking !== null && $booking->status != 'rejected' ){
            return Helper::error("Already have the reservation", Response::HTTP_IM_USED);
        }

        $conflicting = EmployerLeave::where('employer_id', $request['employer_id'])
            ->where('date', $request['date'])
            ->where(function ($query) use ($from_time, $to_time) {
                $query->where(function ($q) use ($from_time, $to_time) {
                    $q->where('from_time', '<=', $from_time)
                        ->where('to_time', '>=', $from_time);
                })->orWhere(function ($q) use ($to_time) {
                    $q->where('from_time', '<=', $to_time)
                        ->where('to_time', '>=', $to_time);
                });
            })
            ->get();

        if (count($conflicting) > 0) {
                return Helper::error("A leave has already been applied for this date", Response::HTTP_NOT_ACCEPTABLE);
        }

        $leave = new EmployerLeave();
        $leave->full_day = $request->half_day_leave == "enabled" ? false : true ;
        $leave->date = $request->date;
        $leave->from_time = $from_time;
        $leave->to_time = $to_time;
        $leave->employer_id = $request->employer_id;
        $leave->reason = $request->reason;
        $leave->status = 0;

        if ($leave->save()) {
            activity('leave')
                ->performedOn($leave)
                ->causedBy(auth()->user())
                ->withProperties(['name' => $leave->employer->user->name])
                ->log('created');

            return new EmployerLeaveResource($leave);
        } else {
            return Helper::error(Response::$statusTexts[Response::HTTP_NO_CONTENT], Response::HTTP_NO_CONTENT);
        }
    }
}
