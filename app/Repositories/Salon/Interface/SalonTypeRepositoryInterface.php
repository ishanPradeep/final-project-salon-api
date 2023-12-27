<?php

namespace App\Repositories\Salon\Interface;

interface SalonTypeRepositoryInterface
{
    public function all($request);
    public function findById($id);
    public function store($request);
}
