<?php

namespace App\Repositories\Review\Interface;

interface EmployerReviewRepositoryInterface
{
    public function store($request);
    public function update($request);
    public function deleteEmployerReview($id);

}
