<?php

namespace App\Repositories\Salon\Interface;

interface PlaceOfferRepositoryInterface
{
    public function all($request);
    public function findById($id);
    public function store($request);
}
