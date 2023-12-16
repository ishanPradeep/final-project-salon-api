<?php

namespace App\Http\Controllers\API\Employer\Leave;

use App\Http\Controllers\Controller;
use App\Models\Employer\Leave\EmployerLeave;
use App\Repositories\Employer\Leave\Interface\EmployerLeaveRepositoryInterface;
use Illuminate\Http\Request;

class EmployerLeaveController extends Controller
{
    private $employerLeaveRepository;

    public function __construct(EmployerLeaveRepositoryInterface $employerLeaveRepository)
    {
        $this->employerLeaveRepository = $employerLeaveRepository;
    }

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        return $this->employerLeaveRepository->all($request);
    }


    /**
     * Display a listing of the resource.
     */
    public function getAllLeavesByEmployerId($employerId)
    {
        return $this->employerLeaveRepository->getAllLeavesByEmployerId($employerId);
    }

    /**
     * Display a listing of the resource.
     */
    public function getAllLeavesBySalonId($salonId)
    {
        return $this->employerLeaveRepository->getAllLeavesBySalonId($salonId);
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
        return $this->employerLeaveRepository->findById($id);
    }

    public function delete($id){
        return $this->employerLeaveRepository->delete($id);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'employer_id'=>'required',
            'date'=>'required',
            'from_time' => 'nullable|required_if:half_day_leave,"enabled"',
            'to_time' => 'nullable|required_if:half_day_leave,"enabled"',
        ]);
        return $this->employerLeaveRepository->store($request);
    }

    public function leaveManage(Request $request){
        $request->validate([
            'leave_id'=>'required',
            'status'=>'required'
        ]);
        return $this->employerLeaveRepository->leaveManage($request);
    }

    /**
     * Display the specified resource.
     */
    public function show(EmployerLeave $employerLeave)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(EmployerLeave $employerLeave)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, EmployerLeave $employerLeave)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(EmployerLeave $employerLeave)
    {
        //
    }
}
