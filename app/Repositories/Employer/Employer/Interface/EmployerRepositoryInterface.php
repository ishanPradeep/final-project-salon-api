<?php

namespace App\Repositories\Employer\Employer\Interface;

interface EmployerRepositoryInterface
{
    public function all($request);
    public function activation($request);
    public function findByName($request);

    public function deleteEmployer($id);
    public function getEmployerfindByUser($userId);
    public function findById($id);
    public function getAllEmployersBySalonId($salonId);
    public function store($request);
    public function update($request);

}
