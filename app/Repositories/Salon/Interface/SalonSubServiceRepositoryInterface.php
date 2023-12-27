<?php

namespace App\Repositories\Salon\Interface;

interface SalonSubServiceRepositoryInterface
{
    public function findById($id);
    public function findBySalonServiceId($id);
    public function deleteSalonSubService($id);
    public function store($request);
    public function findByName($request);

    public function update($request);
}
