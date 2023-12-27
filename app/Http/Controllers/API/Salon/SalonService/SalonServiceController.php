<?php

namespace App\Http\Controllers\API\Salon\SalonService;

use App\Http\Controllers\Controller;
use App\Models\Salon\SalonService\SalonService;
use App\Repositories\Salon\Interface\SalonServiceRepositoryInterface;
use Illuminate\Http\Request;

class SalonServiceController extends Controller
{
    private $salonServiceRepository;

    public function __construct(SalonServiceRepositoryInterface $salonServiceRepository)
    {

        $this->salonServiceRepository = $salonServiceRepository;
    }

    /**
     * Display a listing of the resource.
     */
    public function findById($id)
    {
        return $this->salonServiceRepository->findById($id);
    }

    /**
     * Display a listing of the resource.
     */
    public function findBySalonId($id)
    {
        return $this->salonServiceRepository->findBySalonId($id);
    }


    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        return $this->salonServiceRepository->all($request);
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
            'salon_service_name'=>'required',
            'salon_id'=>'required',
            'service_id'=>'required',
            'banner_images.*' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048',

        ]);
        return $this->salonServiceRepository->store($request);
    }

    /**
     * Display the specified resource.
     */
    public function show(SalonService $spaTypes)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(SalonService $salonService)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request)
    {
        $request->validate([
            'salon_service_id' =>'required',
            'salon_service_name'=>'required',
            'salon_id'=>'required',
            'service_id'=>'required',
            'banner_images.*' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',

        ]);
        return $this->salonServiceRepository->update($request);
    }


    public function deleteSalonServiceBannerImage($id)
    {
        return $this->salonServiceRepository->deleteSalonServiceBannerImage($id);
    }

    public function deleteSalonService($id)
    {
        return $this->salonServiceRepository->deleteSalonService($id);
    }
        /**
     * Remove the specified resource from storage.
     */
    public function destroy(SalonService $salonService)
    {
        //
    }
}
