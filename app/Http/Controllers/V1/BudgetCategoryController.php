<?php

namespace App\Http\Controllers\V1;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Repositories\V1\BudgetCategoryRepository;

class BudgetCategoryController extends Controller
{

    protected $budgetCategory;

    public function __construct(BudgetCategoryRepository $budgetCategoryRepository)
    {
        $this->budgetCategory = $budgetCategoryRepository;
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
        $sortBy = ($request->filled('sort_by')) ? $request->query('sort_by') : "category";
        $sortDirection = ($request->filled('sort_direction')) ? $request->query('sort_direction') : "asc";

        $this->budgetCategory->fetchMany($begin, $perPage, $sortBy, $sortDirection);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
