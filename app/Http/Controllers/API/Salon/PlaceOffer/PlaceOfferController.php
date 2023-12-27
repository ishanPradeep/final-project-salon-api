<?php

namespace App\Http\Controllers\API\Salon\PlaceOffer;

use App\Http\Controllers\Controller;
use App\Models\Salon\PlaceOffer\PlaceOffer;
use App\Repositories\Salon\Interface\PlaceOfferRepositoryInterface;
use Illuminate\Http\Request;

class PlaceOfferController extends Controller
{
    private $placeOfferRepository;

    public function __construct(PlaceOfferRepositoryInterface $placeOfferRepository)
    {
        $this->placeOfferRepository = $placeOfferRepository;
    }

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        return $this->placeOfferRepository->all($request);
    }

    /**
     * Display a listing of the resource.
     */
    public function findById($id)
    {
        return $this->placeOfferRepository->findById($id);
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
            'name'=>'required|unique:place_offers',
            'icon'=>'required',
        ]);

        return $this->placeOfferRepository->store($request);
    }

    /**
     * Display the specified resource.
     */
    public function show(PlaceOffer $placeOffer)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(PlaceOffer $placeOffer)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, PlaceOffer $placeOffer)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(PlaceOffer $placeOffer)
    {
        //
    }
}
