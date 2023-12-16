<?php

namespace App\Http\Controllers\API\Review;

use App\Http\Controllers\Controller;
use App\Models\Review\SalonReview;
use App\Repositories\Review\Interface\SalonReviewRepositoryInterface;
use Illuminate\Http\Request;

class SalonReviewController extends Controller
{
    private $salonReviewRepository;

    public function __construct(SalonReviewRepositoryInterface $salonReviewRepository)
    {
        $this->salonReviewRepository = $salonReviewRepository;
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
            'review' => 'string',
            'salon_id' => 'required',
            'rating' => 'required|numeric|min:0|max:5',
        ]);
        return $this->salonReviewRepository->store($request);
    }

         public function deleteSalonReview($id){
             return $this->salonReviewRepository->deleteSalonReview($id);
         }
    /**
     * Display the specified resource.
     */
    public function show(SalonReview $salonReview)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(SalonReview $salonReview)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
     public function update(Request $request)
     {
         $request->validate([
             'id' => 'required',
             'review' => 'string',
             'rating' => 'required|numeric|min:0|max:5',
         ]);
         return $this->salonReviewRepository->update($request);
     }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(SalonReview $salonReview)
    {
        //
    }
}
