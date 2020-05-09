<?php

namespace App\Http\Controllers\V1;

use Illuminate\Http\Request;
use App\Models\BudgetTracking;
use App\Http\Controllers\Controller;
use App\Repositories\V1\BudgetTrackingRepository;
use App\Http\Requests\CreateBudgetTrackingRequest;
use App\Http\Requests\UpdateBudgetTrackingRequest;

class BudgetTrackingController extends Controller
{
    protected $budgetTracking;
    public function __construct(BudgetTrackingRepository $budgetTrackingRepository)
    {
        $this->budgetTracking = $budgetTrackingRepository;
    }


    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request, int $id)
    {
        $begin = ($request->filled('begin')) ? $request->query('begin') : 0;
        $perPage = ($request->filled('per_page')) ? $request->query('per_page') : 25;
        $sortBy = ($request->filled('sort_by')) ? $request->query('sort_by') : "expenses_created_on";
        $year = ($request->filled('year')) ? $request->query('year') : null;
        $month = ($request->filled('month')) ? $request->query('month') : null;
        $userId = ($request->filled('userId')) ? $request->query('userId') : null;
        $sortDirection = ($request->filled('sort_direction')) ? $request->query('sort_direction') : "asc";

        return $this->budgetTracking->fetchMany($begin, $perPage, $sortBy, $sortDirection, $year, $month, $userId, $id);
    }


    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(CreateBudgetTrackingRequest $request, $id)
    {
        return $this->budgetTracking->create($request->validated(), $id);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\BudgetTracking  $budgetTracking
     * @return \Illuminate\Http\Response
     */
    public function show(int $id, int $trackingId)
    {
        return $this->budgetTracking->fetchOne($id, $trackingId);
    }


    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\BudgetTracking  $budgetTracking
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateBudgetTrackingRequest $request, int $id, int $trackingId)
    {
        return $this->budgetTracking->update($request->validated(), $id, $trackingId);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\BudgetTracking  $budgetTracking
     * @return \Illuminate\Http\Response
     */
    public function destroy(int $id, int $trackingId)
    {
        return $this->budgetTracking->delete($id, $trackingId);
    }
}
