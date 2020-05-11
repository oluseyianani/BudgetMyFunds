<?php

namespace App\Http\Controllers\V1;

use Illuminate\Http\Request;
use App\Models\MonthlyIncome;
use App\Http\Controllers\Controller;
use App\Repositories\V1\MonthlyIncomeRepository;
use App\Http\Requests\CreateMonthlyIncomeRequest;
use App\Http\Requests\UpdateMonthlyIncomeRequest;

class MonthlyIncomeController extends Controller
{
    protected $montlyIncome;
    public function __construct(MonthlyIncomeRepository $monthlyIncomeRepository)
    {
        $this->monthlyIncome = $monthlyIncomeRepository;
    }

    /**
     * Display a listing of the resource.
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $begin = ($request->filled('begin')) ? $request->query('begin') : 0;
        $perPage = ($request->filled('per_page')) ? $request->query('per_page') : 25;
        $sortBy = ($request->filled('sort_by')) ? $request->query('sort_by') : "date_received";
        $year = ($request->filled('year')) ? $request->query('year') : null;
        $month = ($request->filled('month')) ? $request->query('month') : null;
        $userId = ($request->filled('userId')) ? $request->query('userId') : null;
        $sortDirection = ($request->filled('sort_direction')) ? $request->query('sort_direction') : "asc";

        return $this->monthlyIncome->fetchMany($begin, $perPage, $sortBy, $sortDirection, $year, $month, $userId);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(CreateMonthlyIncomeRequest $request)
    {
        return $this->monthlyIncome->create($request->validated());
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(int $id)
    {
        return $this->monthlyIncome->fetchOne($id);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\MonthlyIncome  $monthlyIncome
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateMonthlyIncomeRequest $request, int $id)
    {
        return $this->monthlyIncome->update($request->validated(), $id);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\MonthlyIncome  $monthlyIncome
     * @return \Illuminate\Http\Response
     */
    public function destroy(int $id)
    {
        return $this->monthlyIncome->delete($id);
    }
}
