<?php

namespace App\Http\Controllers\API\Employer;

use App\Http\Controllers\Controller;
use App\Models\Employer\EmployerSalonService;
use App\Repositories\Employer\Employer\Interface\EmployerSalonServiceRepositoryInterface;
use Illuminate\Http\Request;

class EmployerSalonServiceController extends Controller
{
    private $employerSalonServiceRepository;

    public function __construct(EmployerSalonServiceRepositoryInterface $employerSalonServiceRepository)
    {
        $this->employerSalonServiceRepository = $employerSalonServiceRepository;
    }

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        return $this->employerSalonServiceRepository->all($request);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Display a listing of the resource.
     */
    public function findById($id)
    {
        return $this->employerSalonServiceRepository->findById($id);
    }

    public function allEmployerBySalonSubServiceId($salonSubServiceId){
        return $this->employerSalonServiceRepository->allEmployerBySalonSubServiceId($salonSubServiceId);
    }

    public function allSalonSubServiceByEmployerId($employer_id){
        return $this->employerSalonServiceRepository->allSalonSubServiceByEmployerId($employer_id);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'employer_id'=>'required',
            'salon_sub_service_id'=>'required',
            'price'=>'required'

        ]);
        return $this->employerSalonServiceRepository->store($request);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function deleteEmployerFromEmployerSalonService($employer_salon_service_id)
    {
        return $this->employerSalonServiceRepository->deleteEmployerFromEmployerSalonService($employer_salon_service_id);
    }
    /**
     * Display the specified resource.
     */
    public function show(EmployerSalonService $employer)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(EmployerSalonService $employer)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, EmployerSalonService $employer)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(EmployerSalonService $employer)
    {
        //
    }

}
