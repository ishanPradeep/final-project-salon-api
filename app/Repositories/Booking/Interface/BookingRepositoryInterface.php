<?php

namespace App\Repositories\Booking\Interface;

interface BookingRepositoryInterface
{
    public function all($request);
    public function store($request);
    public function getEmployerSubSalonServicesAvailableTime($request);
    public function getEmployerBookings($request);

}
