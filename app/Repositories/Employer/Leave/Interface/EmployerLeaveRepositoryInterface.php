<?php

namespace App\Repositories\Employer\Leave\Interface;

interface EmployerLeaveRepositoryInterface
{
    public function all($request);
    public function leaveManage($request);

    public function findById($id);
    public function delete($id);
    public function getAllLeavesByEmployerId($id);
    public function getAllLeavesBySalonId($salonId);

    public function store($request);
}
