<?php

namespace App\Http\Controllers\V1;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Repositories\V1\BudgetRepository;
use App\Http\Requests\CreateBudgetRequest;
use App\Http\Requests\UpdateBudgetRequest;

class BudgetController extends Controller
{
    protected $budget;
    public function __construct(BudgetRepository $budgetRepository)
    {
        $this->budget = $budgetRepository;
    }


    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $begin = ($request->filled('begin')) ? $request->query('begin') : 0;
        $perPage = ($request->filled('per_page')) ? $request->query('per_page') : 25;
        $sortBy = ($request->filled('sort_by')) ? $request->query('sort_by') : "budget_for_month";
        $year = ($request->filled('year')) ? $request->query('year') : null;
        $month = ($request->filled('month')) ? $request->query('month') : null;
        $userId = ($request->filled('userId')) ? $request->query('userId') : null;
        $sortDirection = ($request->filled('sort_direction')) ? $request->query('sort_direction') : "asc";

        return $this->budget->fetchMany($begin, $perPage, $sortBy, $sortDirection, $year, $month, $userId);
    }


    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(CreateBudgetRequest $request)
    {
        return $this->budget->create($request->validated());
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(int $id)
    {
        return $this->budget->fetchOne($id);
    }


    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateBudgetRequest $request, int $id)
    {
        return $this->budget->update($request->validated(), $id);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(int $id)
    {
        return $this->budget->delete($id);
    }
}
