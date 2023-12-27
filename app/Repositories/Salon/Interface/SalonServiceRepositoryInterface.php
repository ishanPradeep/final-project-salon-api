<?php
namespace App\Repositories\Salon\Interface;

interface SalonServiceRepositoryInterface
{
    public function all($request);
    public function findById($id);
    public function findBySalonId($id);
    public function deleteSalonServiceBannerImage($id);
    public function deleteSalonService($id);

    public function store($request);
    public function update($request);

}
