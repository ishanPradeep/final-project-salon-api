<?php

namespace App\Http\Controllers\API\Salon\SalonSubService;

use App\Http\Controllers\Controller;
use App\Models\Salon\SalonService\SalonSubService\SalonSubService;
use App\Repositories\Salon\Interface\SalonSubServiceRepositoryInterface;
use Illuminate\Http\Request;

class SalonSubServiceController extends Controller
{
    private $salonSubServiceRepository;

    public function __construct(SalonSubServiceRepositoryInterface $salonSubServiceRepository)
    {

        $this->salonSubServiceRepository = $salonSubServiceRepository;
    }

    /**
     * Display a listing of the resource.
     */
    public function findById($id)
    {
        return $this->salonSubServiceRepository->findById($id);
    }

    /**
     * Display a listing of the resource.
     */
    public function findBySalonServiceId($id)
    {
        return $this->salonSubServiceRepository->findBySalonServiceId($id);
    }

    /**
     * Display a listing of the resource.
     */
    public function deleteSalonSubService($id)
    {
        return $this->salonSubServiceRepository->deleteSalonSubService($id);
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
            'sub_service_name'=>'required',
            'salon_service_id'=>'required',
            'hour'=>'required',
            'image' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048',

        ]);
        return $this->salonSubServiceRepository->store($request);
    }

    public function findByName(Request $request)
    {
        return $this->salonSubServiceRepository->findByName($request);
    }


    /**
     * Display the specified resource.
     */
    public function show(SalonSubService $salonSubService)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(SalonSubService $salonSubService)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request)
    {
        $request->validate([
            'sub_service_name'=>'required',
            'salon_service_id'=>'required',
            'hour'=>'required',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);
        return $this->salonSubServiceRepository->update($request);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(SalonSubService $salonSubService)
    {
        //
    }
}
