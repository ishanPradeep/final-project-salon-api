<?php

namespace App\Http\Controllers\API\Employer;

use App\Http\Controllers\Controller;
use App\Models\Employer\Employer;
use App\Repositories\Employer\Employer\Interface\EmployerRepositoryInterface;
use Illuminate\Http\Request;

class EmployerController extends Controller
{
    private $employerRepository;

    public function __construct(EmployerRepositoryInterface $employerRepository)
    {
        $this->employerRepository = $employerRepository;
    }

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        return $this->employerRepository->all($request);
    }

    /**
     * Display a listing of the resource.
     */
    public function activation(Request $request)
    {
        return $this->employerRepository->activation($request);
    }


    /**
     * Display a listing of the resource.
     */
    public function getAllEmployersBySalonId($salonId)
    {
        return $this->employerRepository->getAllEmployersBySalonId($salonId);
    }


    public function findByName(Request $request)
    {
        return $this->employerRepository->findByName($request);
    }

    /**
     * Display a listing of the resource.
     */
    public function getEmployerfindByUser($userId)
    {
        return $this->employerRepository->getEmployerfindByUser($userId);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Display a listing of the resource.
     */
    public function findById($id)
    {
        return $this->employerRepository->findById($id);
    }

    /**
     * Display a listing of the resource.
     */
    public function deleteEmployer($id)
    {
        return $this->employerRepository->deleteEmployer($id);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'user_id'=>'required',
            'salon_id'=>'required'
        ]);
        return $this->employerRepository->store($request);
    }


    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request)
    {
        $request->validate([
            'employer_id'=>'required',
            'salon_id'=>'required'
        ]);
        return $this->employerRepository->update($request);
    }


    /**
     * Display the specified resource.
     */
    public function show(Employer $employer)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Employer $employer)
    {
        //
    }


    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Employer $employer)
    {
        //
    }
}
