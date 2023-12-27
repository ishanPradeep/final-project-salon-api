<?php
namespace App\Repositories\Salon\Interface;

interface SalonRepositoryInterface
{
    public function all($request);
    public function findByName($request);

    public function findById($id);
    public function deleteBannerImage($id);
    public function getAllPendingSalon($request);
    public function changeSalonStatus($request);

    public function store($request);
    public function update($request);


}
