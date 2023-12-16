<?php

namespace App\Http\Controllers\API\Review;

use App\Http\Controllers\Controller;
use App\Models\Review\EmployerReview;
use App\Repositories\Review\Interface\EmployerReviewRepositoryInterface;
use Illuminate\Http\Request;

class EmployerReviewController extends Controller
{
    private $employerReviewRepository;

    public function __construct(EmployerReviewRepositoryInterface $employerReviewRepository)
    {
        $this->employerReviewRepository = $employerReviewRepository;
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
            'employer_id' => 'required',
            'rating' => 'required|numeric|min:0|max:5',
        ]);
        return $this->employerReviewRepository->store($request);
    }

    public function deleteEmployerReview($id){
        return $this->employerReviewRepository->deleteEmployerReview($id);
    }


    /**
     * Display the specified resource.
     */
    public function show(EmployerReview $employerReview)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(EmployerReview $employerReview)
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
        return $this->employerReviewRepository->update($request);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(EmployerReview $employerReview)
    {
        //
    }

}
