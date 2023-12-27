<?php

namespace App\Repositories\Salon\Interface;

interface BannerImageRepositoryInterface
{
    public function all($request);
    public function store($request);
    public function get($id);
}
