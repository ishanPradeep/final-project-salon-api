<?php

namespace App\Repositories\Setting;

use App\Helpers\Helper;
use App\Http\Resources\Salon\ServiceResource;
use App\Http\Resources\Setting\SettingResource;
use App\Models\Salon\Service\Service;
use App\Models\Setting\Setting;
use App\Repositories\Setting\Interface\SettingRepositoryInterface;
use Illuminate\Http\Response;

class SettingRepository implements SettingRepositoryInterface
{
    public function index(){
        $setting = Setting::first();

        if ($setting) {
            return new SettingResource($setting);
        } else {
            return Helper::success(Response::$statusTexts[Response::HTTP_NO_CONTENT], Response::HTTP_NO_CONTENT);
        }
    }
    public function changeSetting($request){
        $setting = Setting::find($request->id);
        $setting->vat = $request->vat;

        if ($setting->save()) {
            activity('service')
                ->performedOn($setting)
                ->causedBy(auth()->user())
                ->withProperties(['name' => 'setting'])
                ->log('updated');

            return new SettingResource($setting);
        } else {
            return Helper::error(Response::$statusTexts[Response::HTTP_NO_CONTENT], Response::HTTP_NO_CONTENT);
        }
    }
}
