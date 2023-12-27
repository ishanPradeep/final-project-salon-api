<?php

namespace App\Repositories\Salon\Interface;

interface ServiceRepositoryInterface
{
    public function all($request);
    public function findById($id);
    public function store($request);
}
