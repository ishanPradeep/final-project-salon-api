<?php

namespace App\Repositories\Employer\Employer;

use App\Helpers\Helper;
use App\Http\Resources\Employer\EmployerCollection;
use App\Http\Resources\Employer\EmployerResource;
use App\Http\Resources\Salon\SalonCollection;
use App\Models\Booking\Booking;
use App\Models\Salon\Salon;
use App\Models\Salon\SalonService\SalonService;
use App\Models\Employer\Employer;
use App\Models\User\User;
use App\Models\User\UserLevel;
use App\Repositories\Employer\Employer\Interface\EmployerRepositoryInterface;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;

class EmployerRepository implements EmployerRepositoryInterface
{


    public function all($request)
    {

        if($request->input('all', '') == 1) {
            $employer_list = Employer::whereHas('salon.user', function ($query) {
                $query->where('user_id', Auth::user()->id);
            })->get();

        } else {
            $employer_list = Employer::whereHas('salon.user', function ($query) {
                $query->where('user_id', Auth::user()->id);
            })->orderBy('created_at', 'desc')->paginate(10);
        }

        if (count($employer_list) > 0) {
            return new EmployerCollection($employer_list);
        } else {
            return Helper::success(Response::$statusTexts[Response::HTTP_NO_CONTENT], Response::HTTP_NO_CONTENT);
        }
    }

    public function getAllEmployersBySalonId($salonId){
        $employer_list = Employer::whereHas('salon.user', function ($query) {
            $query->where('user_id', Auth::user()->id);
        })->where('salon_id',$salonId)->get();


        if (count($employer_list) > 0) {
            return new EmployerCollection($employer_list);
        } else {
            return Helper::success(Response::$statusTexts[Response::HTTP_NO_CONTENT], Response::HTTP_NO_CONTENT);
        }
    }

    public function findByName($request)
    {

        $query = Employer::query();

        if ($request->search != null) {
            $searchTerms = explode(' ', $request->search);

            $query->whereHas('user', function ($q) use ($searchTerms) {
                $q->where(function ($query) use ($searchTerms) {
                    foreach ($searchTerms as $term) {
                        $query->orWhere(function ($query) use ($term) {
                            $query->where('fname', 'like', '%' . $term . '%');
                            $query->orWhere('lname', 'like', '%' . $term . '%');
                        });
                    }
                });
            });
        }
        $query->where('salon_id',$request->salon_id);

        if($request->input('all', '') == 1) {
            $employer_list = $query->get();
        } else {
            $employer_list = Helper::paginate($query->get());
        }

        if (count($employer_list) > 0) {
            return new EmployerCollection($employer_list);
        } else {
            return Helper::success(Response::$statusTexts[Response::HTTP_NO_CONTENT], Response::HTTP_NO_CONTENT);
        }
    }


    public function activation($request)
    {
        $employer = Employer::find($request->employer_id);
        if($employer){
            $employer->publish = $request->is_active;
            $employer->save();
            return new EmployerResource($employer);
        }else {
            return Helper::success(Response::$statusTexts[Response::HTTP_NO_CONTENT], Response::HTTP_NO_CONTENT);
        }
    }
    public function findById($id)
    {

        $employer = Employer::whereHas('salon.user', function ($query) use ($id) {
            $query->where('user_id', Auth::user()->id);
        })->find($id);


        if ($employer) {
            return new EmployerResource($employer);
        } else {
            return Helper::success(Response::$statusTexts[Response::HTTP_NO_CONTENT], Response::HTTP_NO_CONTENT);
        }
    }

    public function getEmployerfindByUser($userId)
    {

        $employer = Employer::where('user_id',$userId)->first();


        if ($employer) {
            return new EmployerResource($employer);
        } else {
            return Helper::success(Response::$statusTexts[Response::HTTP_NO_CONTENT], Response::HTTP_NO_CONTENT);
        }
    }


    public function deleteEmployer($id)
    {

        $employer = Employer::find($id);
        if ($employer) {
            $currentDate = date('Y-m-d');
            $booking = Booking::where('employer_id',$id)
                ->where('date', '>=', $currentDate)
                ->first();

            if($booking !== null && $booking->status != 'rejected'){
                return Helper::error("Can't delete this employer. already have the reservation", Response::HTTP_IM_USED);
            }
            $user = $employer->user;
            if($employer->delete()){
                $user->removeRole('employer');
                $user->assignRole('user');

                activity('deleted')
                    ->performedOn($user)
                    ->causedBy(auth()->user())
                    ->withProperties(['name' => $user->fname])
                    ->log('deleted');

                return Helper::success(Response::$statusTexts[Response::HTTP_OK], Response::HTTP_OK);

            }else{
                return Helper::error(Response::$statusTexts[Response::HTTP_NO_CONTENT], Response::HTTP_NO_CONTENT);
            }
        } else {
            return Helper::error(Response::$statusTexts[Response::HTTP_NO_CONTENT], Response::HTTP_NO_CONTENT);
        }
    }


    public function store($request)
    {
        $user = User::where('active', 1)->find($request->user_id);
        if ($user == null) {
            return Helper::error("User not activated", Response::HTTP_NOT_ACCEPTABLE);
        }
        $has_employe = Employer::where('salon_id', $request->salon_id)->where('user_id', $request->user_id)->first();
        if ($has_employe) {
            return Helper::error("Already exist", Response::HTTP_ALREADY_REPORTED);
        }
        $employer = new Employer();
        $employer->user_id = $request->user_id;
        $employer->salon_id = $request->salon_id;
        $employer->qualifications = $request->qualifications;
        $user->user_level_id = UserLevel::where('scope', 'employer')->first()->id;

        if (!Auth::User()->hasRole('admin') && !Auth::User()->hasRole('super_admin')){
            $user->removeRole('user');
            $user->assignRole('employer');
        }


        if ($employer->save()) {
            $user->save();

            activity('employer')
                ->performedOn($employer)
                ->causedBy(auth()->user())
                ->withProperties(['name' => $employer->user->name])
                ->log('created');

            return new EmployerResource($employer);
        } else {
            return Helper::error(Response::$statusTexts[Response::HTTP_NO_CONTENT], Response::HTTP_NO_CONTENT);
        }
    }

    public function update($request)
    {
        $employer = Employer::find($request->employer_id);
        if($employer) {
            $user = User::where('active', 1)->find($employer->user->id);
            if ($user == null) {
                return Helper::error("User not activated", Response::HTTP_NOT_ACCEPTABLE);
            }
            $employer->qualifications = $request->qualifications;
            $employer->save();
            activity('employer')
                ->performedOn($employer)
                ->causedBy(auth()->user())
                ->withProperties(['name' => $employer->user->name])
                ->log('updated');

            return new EmployerResource($employer);
        } else {
            return Helper::error(Response::$statusTexts[Response::HTTP_NO_CONTENT], Response::HTTP_NO_CONTENT);
        }
    }


}
