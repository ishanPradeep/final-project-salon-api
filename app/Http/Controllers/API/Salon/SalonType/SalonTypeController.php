<?php

namespace App\Http\Controllers\API\Salon\SalonType;

use App\Http\Controllers\Controller;
use App\Models\Salon\SalonType\SalonType;
use App\Repositories\Salon\Interface\SalonTypeRepositoryInterface;
use Illuminate\Http\Request;

class SalonTypeController extends Controller
{
    private $salonTypeRepository;

    public function __construct(SalonTypeRepositoryInterface $salonTypeRepository)
    {
        $this->salonTypeRepository = $salonTypeRepository;
    }

    /**
     * Display a listing of the resource.
     */
    public function findById($id)
    {
        return $this->salonTypeRepository->findById($id);
    }

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        return $this->salonTypeRepository->all($request);
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
            'title'=>'required|unique:salon_types',
            'subtitle'=>'required',
            'icon'=>'required'
        ]);
        return $this->salonTypeRepository->store($request);
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
