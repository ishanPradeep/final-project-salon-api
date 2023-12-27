<?php

namespace App\Repositories\Setting\Interface;

interface SettingRepositoryInterface
{
    public function index();
    public function changeSetting($request);
}
