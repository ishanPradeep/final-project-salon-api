<?php

namespace App\Repositories\Employer\WorkingDay\Interface;

interface EmployerWorkingDayRepositoryInterface
{
    public function all($request);
//    public function findById($day_id);

    public function store($request);
}
