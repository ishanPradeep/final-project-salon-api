<?php

namespace App\Repositories\Review\Interface;

interface SalonReviewRepositoryInterface
{
    public function store($request);
    public function update($request);
    public function deleteSalonReview($id);

}
