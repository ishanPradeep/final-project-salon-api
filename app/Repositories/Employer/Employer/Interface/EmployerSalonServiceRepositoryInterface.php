<?php

namespace App\Repositories\Employer\Employer\Interface;

interface EmployerSalonServiceRepositoryInterface
{
    public function all($request);
    public function findById($id);
    public function store($request);
    public function deleteEmployerFromEmployerSalonService($employer_salon_service_id);

    public function allEmployerBySalonSubServiceId($salonSubServiceId);
    public function allSalonSubServiceByEmployerId($employer_id);
}
