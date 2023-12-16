<?php

namespace App\Http\Controllers\API\Salon;

use App\Http\Controllers\Controller;
use App\Models\Salon\Salon;
use App\Repositories\Salon\Interface\SalonRepositoryInterface;
use App\Rules\Base64Image;
use Illuminate\Http\Request;

class SalonController extends Controller
{
    private $salonRepository;

    public function __construct(SalonRepositoryInterface $salonRepository)
    {
        $this->salonRepository = $salonRepository;
    }

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        return $this->salonRepository->all($request);
    }

    /**
     * Display a listing of the resource.
     */
    public function findByName(Request $request)
    {
        return $this->salonRepository->findByName($request);
    }


    /**
     * Display a listing of the resource.
     */
    public function findById($id)
    {
        return $this->salonRepository->findById($id);
    }

    /**
     * Display a listing of the resource.
     */
    public function getAllPendingSalon(Request $request)
    {
        return $this->salonRepository->getAllPendingSalon($request);
    }

    /**
     * Display a listing of the resource.
     */
    public function changeSalonStatus(Request $request)
    {
        return $this->salonRepository->changeSalonStatus($request);
    }


    public function deleteBannerImage($id)
    {
        return $this->salonRepository->deleteBannerImage($id);
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
            'name'=>'required',
            'email' => 'required|email|unique:salons',
            'contact_number'=>'required',
            'address'=>'required',
            'latitude'=>'required',
            'longitude'=>'required',
            "salon_type_id"=>"required",
            'salon_logo' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048',
            'banner_images.*' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048',
            "weekday_start"=>'required',
            "weekday_end"=>'required',
            "weekend_start"=>'required',
            "weekend_end"=>'required'
        ]);

        return $this->salonRepository->store($request);
    }

    /**
     * Display the specified resource.
     */
    public function show(Salon $salon)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Salon $salon)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function updateSalon(Request $request)
    {
        $request->validate([
            'salon_id'=>'required',
            'name'=>'required',
            'email' => 'email|unique:salons,email,' . $request->salon_id . ',id',
            'contact_number'=>'required',
            'address'=>'required',
            'latitude'=>'required',
            'longitude'=>'required',
            "salon_type_id"=>"required",
            'salon_logo' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'banner_images.*' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            "weekday_start"=>'required',
            "weekday_end"=>'required',
            "weekend_start"=>'required',
            "weekend_end"=>'required'
        ]);

        return $this->salonRepository->update($request);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Salon $salon)
    {
        //
    }
}
