<?php

namespace App\Repositories\Booking;

use App\Helpers\Helper;
use App\Helpers\Utility;
use App\Http\Resources\Booking\BookingCollection;
use App\Http\Resources\Employer\EmployerCollection;
use App\Models\Booking\Booking;
use App\Models\Employer\Employer;
use App\Models\Employer\EmployerSalonService;
use App\Models\Employer\Leave\EmployerLeave;
use App\Models\Employer\WorkingDay\EmployerWorkingDay;
use App\Models\Salon\SalonService\SalonSubService\SalonSubService;
use App\Models\Setting\Setting;
use App\Models\User\UserLevel;
use App\Repositories\Booking\Interface\BookingRepositoryInterface;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;

class BookingRepository implements BookingRepositoryInterface
{
    public function all($request)
    {

        if($request->input('all', '') == 1) {
            $booking_list = Booking::where('user_id',Auth::user()->id)->get();

        } else {
            $booking_list = Booking::where('user_id',Auth::user()->id)->orderBy('created_at', 'desc')->paginate(10);
        }

        if (count($booking_list) > 0) {
            return new BookingCollection($booking_list);
        } else {
            return Helper::success(Response::$statusTexts[Response::HTTP_NO_CONTENT], Response::HTTP_NO_CONTENT);
        }
    }

    public function getEmployerBookings($request)
    {
        if($request->input('all', '') == 1) {
            $booking_list = Booking::whereHas('salonSubService.salonService.salon', function ($query) use ($request) {
                $query->where('id', $request->salon_id);
            })->where('employer_id',$request->employer_id)->get();

        } else {
            $booking_list = Booking::whereHas('salonSubService.salonService.salon', function ($query) use ($request) {
                $query->where('id', $request->salon_id);
            })->where('employer_id',$request->employer_id)
                ->orderBy('created_at', 'desc')->paginate(10);
        }

        if (count($booking_list) > 0) {
            return new BookingCollection($booking_list);
        } else {
            return Helper::success(Response::$statusTexts[Response::HTTP_NO_CONTENT], Response::HTTP_NO_CONTENT);
        }
    }

    public function changeBookingStatus($request){

        $user_level = UserLevel::find(auth()->user()->user_level_id);
        if ($user_level->scope == 'admin') {
            $booking = Booking::whereHas('salonSubService.salonService.salon', function ($query) {
                $query->where('user_id', auth()->user()->id);
            })->find( $request->booking_id);
        } elseif ($user_level->scope == 'employer') {
            $booking = Booking::whereHas('employer', function ($query) {
                $query->where('user_id', auth()->user()->id);
            })->find($request->booking_id);
        }else{
            return Helper::error(Response::$statusTexts[Response::HTTP_NOT_ACCEPTABLE], Response::HTTP_NOT_ACCEPTABLE);
        }
        if ($booking) {
            $booking->status = $request->status;
            $booking->save();

            activity('booking-status')
                ->performedOn($booking)
                ->causedBy(auth()->user())
                ->withProperties(['name' => $booking->salonSubService->sub_service_name])
                ->log('updated');
            return Helper::success(Response::$statusTexts[Response::HTTP_OK], Response::HTTP_OK);

        } else {
            return Helper::error(Response::$statusTexts[Response::HTTP_NO_CONTENT], Response::HTTP_NO_CONTENT);
        }
    }
    public function store($request)
    {

        $employerSalonService = EmployerSalonService::where('salon_sub_service_id',$request->salon_sub_service_id)->
        where('employer_id',$request->employer_id)->first();
        $salon_sub_service = SalonSubService::find($request->salon_sub_service_id);
        $setting = Setting::first();
        $booking = new Booking();
        $booking->user_id = Auth::user()->id;
        $booking->salon_sub_service_id = $request->salon_sub_service_id;
        $booking->employer_id = $request->employer_id;
        $booking->sub_service_name = $salon_sub_service->sub_service_name;
        $booking->from_time = $request->from_time;
        $booking->to_time = $request->to_time;
        $booking->date = $request->date;
        $booking->duration = $salon_sub_service->hour;
        $booking->actual_price = $employerSalonService->price;
        $booking->vat = $setting->vat;
        $booking->service_discount = 0;
        $booking->offer_discount = 0;
        $tempPrice = $booking->actual_price + ($booking->actual_price * $setting->vat /100);
        $booking->price = $tempPrice - ($booking->service_discount + $booking->offer_discount);
        $booking->status = $salon_sub_service->auto_approval == 1 ? 'approved' : 'pending';
        $booking->booking_cancellation = $salon_sub_service->booking_cancellation;

        if ($booking->save()) {

            activity('booking-request')
                ->performedOn($booking)
                ->causedBy(auth()->user())
                ->withProperties(['name' => $salon_sub_service->sub_service_name])
                ->log('created');

            return Helper::success(Response::$statusTexts[Response::HTTP_OK], Response::HTTP_OK);
        } else {
            return Helper::error(Response::$statusTexts[Response::HTTP_NO_CONTENT], Response::HTTP_NO_CONTENT);
        }
    }

    public function getEmployerSubSalonServicesAvailableTime($request)
    {
        $salonSubService = SalonSubService::find($request->sub_service_id);
        $employerSalonService = EmployerSalonService::where('salon_sub_service_id',$request->sub_service_id)
            ->where('employer_id',$request->employer_id)->first();

        $employerWorkingDay = $this->getEmployerAvalableDays($request->employer_id);
        $booked_services = $this->booked_service($request->employer_id);
        $employer_leaves = $this->EmployerLeaves($request->employer_id);

        $buffer_time = $employerSalonService->buffer_time;
        $duration = $salonSubService->hour;
        $checkingDate = date('d-m-Y', strtotime($request->date));
        $utility = new Utility();
        $out_put = [];
        $dateNumber = $this->getDayNumber($checkingDate);
        $available_times = $this->getAvailableTimesForPartucalrDayNumber($employerWorkingDay, $dateNumber);
        $available_times = $this->convert_available_time_slot_format($available_times);
        $booking_for_particular_date = $this->getBookingForParticularDate($booked_services, $checkingDate);

        $booking_for_particular_date = $this->convert_time_slot_format($booking_for_particular_date);
        $employer_leaves_for_particular_date = $this->getEmployerLeavesForParticularDate($employer_leaves, date('d-m-Y', strtotime($checkingDate)));
        $employer_leaves_for_particular_date = $this->convert_time_slot_format($employer_leaves_for_particular_date);
        $leave_results = $utility->removeLeaveSlots($available_times, $employer_leaves_for_particular_date);
        $available_times = $utility->removeOccupiedSlots($leave_results, $booking_for_particular_date, $buffer_time);

        $final_result = $this->finalresultGenerate($available_times, $checkingDate, $duration);
        $temp = ['slots' => $final_result];
        array_push($out_put, $temp);

        $checkingDate = date('d-m-Y', strtotime($checkingDate . " + 1 days"));
        return json_encode($out_put);
    }

    public function getEmployerAvalableDays($employer_id)
    {
        return EmployerWorkingDay::select('day_id', 'from_time', 'to_time')->where('employer_id', $employer_id)->get()->toArray();
    }

    public function booked_service($employer_id)
    {
        return Booking::where('employer_id',$employer_id)->get()->toArray();
    }

    public function convert_time_slot_format($employerWorkingDays)
    {
        $temp = [];
        foreach ($employerWorkingDays as $key => $employerWorkingDay) {
            array_push($temp, [$employerWorkingDay['from_time'], $employerWorkingDay['to_time']]);
        }
        return $temp;
    }

    public function employerLeaves($employer_id)
    {
        return EmployerLeave::select('date', 'from_time', 'to_time')->where('employer_id', $employer_id)->get()->toArray();
    }

    public function getDayNumber($date)
    {
        $dateName = date("D", strtotime($date));
        switch ($dateName) {
            case 'Mon':
                return 2;
                break;
            case 'Tue':
                return 3;
                break;

            case 'Wed':
                return 4;
                break;

            case 'Thu':
                return 5;
                break;

            case 'Fri':
                return 6;
                break;

            case 'Sat':
                return 7;
                break;

            case 'Sun':
                return 1;
                break;

            default:
                break;
        }
    }

    public function getAvailableTimesForPartucalrDayNumber($employerWorkingDay, $dateNumber)
    {
        return array_filter($employerWorkingDay, function ($obj) use ($dateNumber) {
            if (isset($obj['day_id'])) {
                if ($obj['day_id'] != $dateNumber) return false;
            }
            return true;
        });
    }

    public function convert_available_time_slot_format($employerWorkingDays)
    {
        $temp = [];
        foreach ($employerWorkingDays as $key => $employerWorkingDay) {
            array_push($temp, [$employerWorkingDay['from_time'], $employerWorkingDay['to_time']]);
        }
        return $temp;
    }

    public function getBookingForParticularDate($booked_services, $day)
    {

        return array_filter($booked_services, function ($obj) use ($day) {
            if (isset($obj['date'])) {
                $day = date('Y-m-d', strtotime($day));
                if ($obj['date'] != $day) return false;
            }
            return true;
        });
    }

    public function getEmployerLeavesForParticularDate($employer_leaves, $day)
    {
        return array_filter($employer_leaves, function ($obj) use ($day) {
            $obj_date = date('d-m-Y', strtotime($obj['date']) );
            if (isset($obj_date)) {
                if ($obj_date != $day) return false;
            }
            return true;
        });
    }

    public function finalresultGenerate($available_slots, $checkingDate, $duration)
    {
        $temp = [];
        $slotDuration = $duration*60; // hours to minutes
        foreach ($available_slots as $key => $slot) {
            $from_time = date('H:i', strtotime($checkingDate . $slot[0])); // Get the time part
            $to_time = date('H:i', strtotime($checkingDate . $slot[1])); // Get the time part

            $minutes = (strtotime($to_time) - strtotime($from_time));

            while (($minutes - $slotDuration) >= 0) {
                $slotEnd = date('H:i', strtotime($from_time) + $slotDuration * 60);

                $formated_slots = [
                    'date' =>  date('Y-m-d', strtotime($checkingDate) ),
                    'start' => $from_time,
                    'end' => $slotEnd,
                    'start_end' => $from_time.' - '.$slotEnd,
                    'title' => $from_time . " to " . $slotEnd . " available"
                ];

                array_push($temp, $formated_slots);

                // Move to the next 2-hour slot
                $from_time = $slotEnd;
                $minutes -= $slotDuration;
            }
        }

        return $temp;
    }

}
