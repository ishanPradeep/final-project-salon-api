<?php

namespace App\Http\Controllers\API\Employer\WorkingDay;

use App\Http\Controllers\Controller;
use App\Models\Employer\WorkingDay\EmployerWorkingDay;
use App\Repositories\Employer\WorkingDay\Interface\EmployerWorkingDayRepositoryInterface;
use Illuminate\Http\Request;

class EmployerWorkingDayController extends Controller
{
    private $employerWorkingDayRepository;

    public function __construct(EmployerWorkingDayRepositoryInterface $employerWorkingDayRepository)
    {
        $this->employerWorkingDayRepository = $employerWorkingDayRepository;
    }

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        return $this->employerWorkingDayRepository->all($request);
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
//    public function findById($day_id)
//    {
//        return $this->employerWorkingDayRepository->findById($day_id);
//    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'day_id'=>'required',
            'employer_id'=>'required',
            'from_time'=>'required',
            'to_time'=>'required'

        ]);
        return $this->employerWorkingDayRepository->store($request);
    }

    /**
     * Display the specified resource.
     */
    public function show(EmployerWorkingDay $employerWorkingDay)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(EmployerWorkingDay $employerWorkingDay)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, EmployerWorkingDay $employerWorkingDay)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(EmployerWorkingDay $employerWorkingDay)
    {
        //
    }
}
