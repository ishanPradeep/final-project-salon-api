<?php

namespace App\Http\Controllers\API\Booking;

use App\Http\Controllers\Controller;
use App\Models\Booking\Booking;
use App\Repositories\Booking\Interface\BookingRepositoryInterface;
use Illuminate\Http\Request;

class BookingController extends Controller
{
    private $bookingRepository;

    public function __construct(BookingRepositoryInterface $bookingRepository)
    {
        $this->bookingRepository = $bookingRepository;
    }

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        return $this->bookingRepository->all($request);
    }

    public function getEmployerSubSalonServicesAvailableTime(Request $request)
    {
        return $this->bookingRepository->getEmployerSubSalonServicesAvailableTime($request);
    }

    public function getEmployerBookings(Request $request)
    {
        return $this->bookingRepository->getEmployerBookings($request);
    }


    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {

        $request->validate([
            'salon_sub_service_id' => 'required',
            'employer_id'=>'required',
            'from_time'=>'required',
            'to_time'=>'required',
            'date'=>'required'
        ]);
        return $this->bookingRepository->store($request);
    }

    public function changeBookingStatus(Request $request)
    {

        $request->validate([
            'booking_id' => 'required',
            'status' => 'required'
        ]);
        return $this->bookingRepository->changeBookingStatus($request);
    }

    /**
     * Display the specified resource.
     */
    public function show(Booking $booking)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Booking $booking)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Booking $booking)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Booking $booking)
    {
        //
    }
}
