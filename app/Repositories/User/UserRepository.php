<?php

namespace App\Repositories\User;

use App\Helpers\Helper;
use App\Http\Resources\Salon\SalonResource;
use App\Http\Resources\User\AuthUserResource;
use App\Http\Resources\User\UserCollection;
use App\Http\Resources\User\UserResource;
use App\Models\User\User;
use App\Models\User\UserLevel;
use App\Repositories\User\Interface\UserRepositoryInterface;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Intervention\Image\Facades\Image;

class UserRepository implements UserRepositoryInterface
{

    public function all($request)
    {

        if($request->input('all', '') == 1) {

            $user_list = User::where('user_level_id', '!=', UserLevel::where('scope', 'super_admin')->first()->id)->get();
        } else {
            $user_list = User::where('user_level_id', '!=', UserLevel::where('scope', 'super_admin')->first()->id)->orderBy('created_at', 'desc')->paginate(10);
        }

        if (count($user_list) > 0) {
            return new UserCollection($user_list);
        } else {
            return Helper::success(Response::$statusTexts[Response::HTTP_NO_CONTENT], Response::HTTP_NO_CONTENT);
        }
    }

    public function update($request)
    {


        $user = User::find($request->id);

        if ($request->file('profile_photo')) {
            $disk = Storage::disk('s3');
            if ($user->profile_photo && $disk->exists($user->profile_photo)) {
                $disk->delete($user->profile_photo);
            }

            $image = $request->file('profile_photo');
            $filename = 'user/profile/' . uniqid() . '.' . $image->getClientOriginalExtension();
            $disk->put($filename, file_get_contents($image));
            $user->profile_photo = $filename;
        }

        $user->fname = $request->fname;
        $user->lname = $request->lname;
        $user->address = $request->address;
        $user->latitude = $request->latitude;
        $user->longitude = $request->longitude;
        $user->contact_no = $request->contact_no;

        if ($user->save()) {
            activity('user')->causedBy($user)->performedOn($user)->log('updated');
            return new UserResource($user);
        } else {
            return Helper::error(Response::$statusTexts[Response::HTTP_NO_CONTENT], Response::HTTP_NO_CONTENT);
        }
    }


}
