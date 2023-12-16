<?php

namespace App\Http\Controllers\API\Salon\Service;

use App\Http\Controllers\Controller;
use App\Models\Salon\SalonType\SalonType;
use App\Repositories\Salon\Interface\ServiceRepositoryInterface;
use Illuminate\Http\Request;

class ServiceController extends Controller
{
    private $serviceRepository;

    public function __construct(ServiceRepositoryInterface $serviceRepository)
    {
        $this->serviceRepository = $serviceRepository;
    }

    /**
     * Display a listing of the resource.
     */
    public function findById($id)
    {
        return $this->serviceRepository->findById($id);
    }

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        return $this->serviceRepository->all($request);
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
            'name'=>'required|unique:services',
            'salon_type_id'=>'required',
            'icon'=>'required'
        ]);
        return $this->serviceRepository->store($request);
    }

    /**
     * Display the specified resource.
     */
    public function show(SalonType $treatment)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(SalonType $treatment)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, SalonType $treatment)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(SalonType $treatment)
    {
        //
    }
}
