<?php

namespace App\Repositories\Employer\WorkingDay;

use App\Helpers\Helper;
use App\Http\Resources\Employer\Leave\EmployerLeaveResource;
use App\Http\Resources\Employer\WorkingDay\EmployerWorkingDayCollection;
use App\Http\Resources\Employer\WorkingDay\EmployerWorkingDayResource;
use App\Models\Employer\Employer;
use App\Models\Employer\WorkingDay\EmployerWorkingDay;
use App\Repositories\Employer\WorkingDay\Interface\EmployerWorkingDayRepositoryInterface;
use Illuminate\Http\Response;

class EmployerWorkingDayRepository implements EmployerWorkingDayRepositoryInterface
{
    public function all($request)
    {

        if($request->input('all', '') == 1) {
            $working_day_list = EmployerWorkingDay::where('employer_id',$request->employer_id)->get();
        } else {
            $working_day_list = EmployerWorkingDay::where('employer_id',$request->employer_id)->orderBy('created_at', 'desc')->paginate(10);
        }

        if (count($working_day_list) > 0) {
            return new EmployerWorkingDayCollection($working_day_list);
        } else {
            return Helper::success(Response::$statusTexts[Response::HTTP_NO_CONTENT], Response::HTTP_NO_CONTENT);
        }
    }
//    public function findById($day_id)
//    {
//
//        $working_days = EmployerWorkingDay::where('day_id',$day_id)->get();
//
//        if ($working_days) {
//            return new EmployerWorkingDayCollection($working_days);
//        } else {
//            return Helper::success(Response::$statusTexts[Response::HTTP_NO_CONTENT], Response::HTTP_NO_CONTENT);
//        }
//    }

    public function store($request)
    {
        if (!Employer::find($request->employer_id)){
            return Helper::error(Response::$statusTexts[Response::HTTP_NO_CONTENT], Response::HTTP_NO_CONTENT);
        }
        $working_day = new EmployerWorkingDay();
        $working_day->day_id = $request->day_id;
        $working_day->from_time = $request->from_time;
        $working_day->to_time = $request->to_time;
        $working_day->employer_id = $request->employer_id;

        $conflictExists = $this->checkForConflicts($working_day);

        if ($conflictExists) {
            return response()->json(['message' => 'Conflict with existing working day.'], 409);
        }

        if ($working_day->save()) {
            activity('working_day')
                ->performedOn($working_day)
                ->causedBy(auth()->user())
                ->withProperties(['name' => $working_day->employer->user->name])
                ->log('created');

            return new EmployerWorkingDayResource($working_day);
        } else {
            return Helper::error(Response::$statusTexts[Response::HTTP_NO_CONTENT], Response::HTTP_NO_CONTENT);
        }
    }


    protected function checkForConflicts($newWorkingDay)
    {
        $conflictingDays = EmployerWorkingDay::where('employer_id', $newWorkingDay->employer_id)
            ->where('day_id', $newWorkingDay->day_id)
            ->where(function ($query) use ($newWorkingDay) {
                $query->where(function ($q) use ($newWorkingDay) {
                    $q->where('from_time', '<', $newWorkingDay->to_time)
                        ->where('to_time', '>', $newWorkingDay->from_time);
                });
            })
            ->get();

        return $conflictingDays->isNotEmpty();
    }
}
