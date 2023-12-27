<?php

namespace App\Repositories\Salon;

use App\Helpers\Helper;
use App\Http\Resources\Salon\SalonCollection;
use App\Http\Resources\Salon\SalonResource;
use App\Mail\MailQueue;
use App\Models\Booking\Booking;
use App\Models\Salon\PlaceOffer\SalonPlaceOffer;
use App\Models\Salon\Salon;
use App\Models\Salon\SalonBannerImage;
use App\Models\Salon\SalonOpenEndTime;
use App\Models\Salon\SalonService\BannerImage\SalonServiceBannerImage;
use App\Repositories\Salon\Interface\SalonRepositoryInterface;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Intervention\Image\Facades\Image;
use Mockery\Exception;

class SalonRepository implements SalonRepositoryInterface
{

    public function all($request)
    {

        if($request->input('all', '') == 1) {
            if (Auth::User()->hasRole('super_admin')){
                $salon_list = Salon::get();
            }else{
                $salon_list = Salon::where('user_id',Auth::user()->id)->get();
            }

        } else {
            if (Auth::User()->hasRole('super_admin')){
                $salon_list = Salon::orderBy('created_at', 'desc')->paginate(10);
            }else{
                $salon_list = Salon::where('user_id',Auth::user()->id)->orderBy('created_at', 'desc')->paginate(10);
            }
        }

        if (count($salon_list) > 0) {
            return new SalonCollection($salon_list);
        } else {
            return Helper::success(Response::$statusTexts[Response::HTTP_NO_CONTENT], Response::HTTP_NO_CONTENT);
        }
    }
    public function findById($id)
    {
        if (Auth::User()->hasRole('super_admin')){
            $salon = Salon::find($id);
        }else{
            $salon = Salon::where('user_id',Auth::user()->id)->find($id);
        }

        if ($salon) {
            return new SalonResource($salon);
        } else {
            return Helper::success(Response::$statusTexts[Response::HTTP_NO_CONTENT], Response::HTTP_NO_CONTENT);
        }
    }
    public function findByName($request)
    {

        $query = Salon::query();

        if ($request->search != null) {
            $query->where('name', 'like', '%' . $request->search . '%');
        }
        if (Auth::User()->hasRole('super_admin')){
        }else{
            $query->where('user_id',Auth::user()->id);
        }
        if($request->input('all', '') == 1) {
            $salon_list = $query->get();
        } else {
            $salon_list = Helper::paginate($query->get());
        }

        if (count($salon_list) > 0) {
            return new SalonCollection($salon_list);
        } else {
            return Helper::success(Response::$statusTexts[Response::HTTP_NO_CONTENT], Response::HTTP_NO_CONTENT);
        }
    }
    public function store($request)
    {
        $salon = new Salon();

        if ($request->file('salon_logo')){
            $image = $request->file('salon_logo');
            $filename = 'salon/logo/' . uniqid() . '.' . $image->getClientOriginalExtension();
            Storage::disk('s3')->put($filename, file_get_contents($image));
            $salon->salon_logo = $filename;
        }

        $salon->name = $request->name;
        $salon->email = $request->email;
        $salon->description = $request->description;
        $salon->user_id = Auth::user()->id;
        $salon->salon_type_id = $request->salon_type_id;
        $salon->contact_number = $request->contact_number;
        $salon->address = $request->address;
        $salon->latitude = $request->latitude;
        $salon->longitude = $request->longitude;
        $salon->publish = false;
        if (!Auth::User()->hasRole('admin') && !Auth::User()->hasRole('super_admin')){
            Auth::User()->removeRole('user');
            Auth::User()->assignRole('admin');
            Auth::User()->user_level_id = 2;
        }

        if ($salon->save()) {
            Auth::User()->save();
            $place_offers = json_decode($request->place_offers);
            $salon->placeOffers()->attach($place_offers);

            for ($day = 1; $day <= 7; $day++) {
                $openTime = new SalonOpenEndTime();
                if($day == 1 || $day == 7){
                    $openTime->day_id = $day;
                    $openTime->salon_id= $salon->id;
                    $openTime->from_time = $request->weekend_start;
                    $openTime->to_time = $request->weekend_end;
                    $openTime->is_open = true;
                }else{
                    $openTime->day_id = $day;
                    $openTime->is_open = true;
                    $openTime->salon_id= $salon->id;
                    $openTime->from_time = $request->weekday_start;
                    $openTime->to_time = $request->weekday_end;
                }
                $openTime->save();
            }


            if ($request->file('banner_images')){
                foreach ($request->file('banner_images') as $key => $imagefile) {
                    $banner_image = new SalonBannerImage();
                    $banner_file_name = 'salon/banner/' . uniqid() . '.' . $imagefile->getClientOriginalExtension();
                    Storage::disk('s3')->put($banner_file_name, file_get_contents($imagefile));
                    $banner_image->path = $banner_file_name;
                    $banner_image->salon_id = $salon->id;
                    $banner_image->save();
                }
            }

            activity('salon')
                ->performedOn($salon)
                ->causedBy(auth()->user())
                ->withProperties(['name' => $salon->name])
                ->log('created');

            return new SalonResource($salon);
        } else {
            return Helper::error(Response::$statusTexts[Response::HTTP_NO_CONTENT], Response::HTTP_NO_CONTENT);
        }
    }
    public function update($request)
    {
        $salon = Salon::find($request->salon_id);
        if ($request->file('salon_logo')) {
            $disk = Storage::disk('s3');
            if ($disk->exists($salon->salon_logo)) {
                $disk->delete($salon->salon_logo);
            }

            $image = $request->file('salon_logo');
            $filename = 'salon/logo/' . uniqid() . '.' . $image->getClientOriginalExtension();
            $disk->put($filename, file_get_contents($image));
            $salon->salon_logo = $filename;
        }

        $salon->name = $request->name;
        $salon->email = $request->email;
        $salon->description = $request->description;
        $salon->salon_type_id = $request->salon_type_id;
        $salon->contact_number = $request->contact_number;
        $salon->address = $request->address;
        $salon->latitude = $request->latitude;
        $salon->longitude = $request->longitude;

        $salonOpenEndTime = SalonOpenEndTime::where('salon_id',$salon->id)->get();
        foreach ($salonOpenEndTime as $item) {
            $item->delete();
        }
        for ($day = 1; $day <= 7; $day++) {
            $openTime = new SalonOpenEndTime();
            if($day == 1 || $day == 7){
                $openTime->day_id = $day;
                $openTime->salon_id= $salon->id;
                $openTime->from_time = $request->weekend_start;
                $openTime->to_time = $request->weekend_end;
                $openTime->is_open = true;
            }else{
                $openTime->day_id = $day;
                $openTime->is_open = true;
                $openTime->salon_id= $salon->id;
                $openTime->from_time = $request->weekday_start;
                $openTime->to_time = $request->weekday_end;
            }
            $openTime->save();
        }

        $salon->placeOffers()->sync(json_decode($request->place_offers));

        if ($request->file('banner_images')){
            foreach ($request->file('banner_images') as $key => $imagefile) {
                $banner_image = new SalonBannerImage();
                $banner_file_name = 'salon/banner/' . uniqid() . '.' . $imagefile->getClientOriginalExtension();
                Storage::disk('s3')->put($banner_file_name, file_get_contents($imagefile));
                $banner_image->path = $banner_file_name;
                $banner_image->salon_id = $salon->id;
                $banner_image->save();
            }
        }

        if ($salon->save()) {
            activity('salon')
                ->performedOn($salon)
                ->causedBy(auth()->user())
                ->withProperties(['name' => $salon->name])
                ->log('updated');

            return new SalonResource($salon);
        } else {
            return Helper::error(Response::$statusTexts[Response::HTTP_NO_CONTENT], Response::HTTP_NO_CONTENT);
        }
    }
    public function getAllPendingSalon($request)
    {
        if($request->input('all', '') == 1) {
            if (Auth::User()->hasRole('super_admin')){
                $salon_list = Salon::where('publish',0)->get();
            }else{
                $salon_list = Salon::where('user_id',Auth::user()->id)->where('publish',0)->get();
            }

        } else {
            if (Auth::User()->hasRole('super_admin')){
                $salon_list = Salon::where('publish',0)->orderBy('created_at', 'desc')->paginate(10);
            }else{
                $salon_list = Salon::where('user_id',Auth::user()->id)->where('publish',0)->orderBy('created_at', 'desc')->paginate(10);
            }
        }

        if (count($salon_list) > 0) {
            return new SalonCollection($salon_list);
        } else {
            return Helper::success(Response::$statusTexts[Response::HTTP_NO_CONTENT], Response::HTTP_NO_CONTENT);
        }
    }
    public function changeSalonStatus($request)
    {
        $salon = Salon::find($request->salon_id);

        if($request->status == false){
            $currentDate = date('Y-m-d');
            $booking = Booking::whereHas('salonSubService.salonService.salon', function ($query) use ($request) {
                $query->where('id', $request->salon_id);
            })
                ->where('date', '>=', $currentDate)
                ->first();

            if($booking !== null && $booking->status != 'rejected' ){
                return Helper::error("Already have the reservation", Response::HTTP_IM_USED);
            }
        }
        $salon->publish = $request->status;

        if ($salon->save()) {

            Mail::to($salon->email)
                ->queue(new MailQueue([
                    'subject' => 'Your salon status has been updated',
                    'template' => 'change_salon_status_email',
                    'user' => Auth::user(),
                    'salon' => $salon
                ]));

            activity('salon-status')
                ->performedOn($salon)
                ->causedBy(auth()->user())
                ->withProperties(['name' => $salon->name])
                ->log('updated');
            return new SalonResource($salon);
        } else {
            return Helper::success(Response::$statusTexts[Response::HTTP_NO_CONTENT], Response::HTTP_NO_CONTENT);
        }
    }
    public function deleteBannerImage($id){
        $salonBannerImage = SalonBannerImage::find($id);
        if ($salonBannerImage){
            $salon = Salon::find($salonBannerImage->salon_id);
            if ($salonBannerImage) {
                $disk = Storage::disk('s3');
                if ($disk->exists($salonBannerImage->path)) {
                    $disk->delete($salonBannerImage->path);
                    $salonBannerImage->delete();
                }
            }
            return new SalonResource($salon);
        }
        return Helper::error(Response::$statusTexts[Response::HTTP_NO_CONTENT], Response::HTTP_NO_CONTENT);
    }

}
