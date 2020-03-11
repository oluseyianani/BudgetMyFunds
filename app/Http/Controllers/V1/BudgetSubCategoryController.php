<?php

namespace App\Http\Controllers\V1;

use App\Models\Category;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Requests\CreateSubCategoryRequest;
use App\Http\Requests\UpdateSubCategoryRequest;
use App\Repositories\V1\BudgetSubCategoryRepository;

class BudgetSubCategoryController extends Controller
{
    protected $budgetSubCategory;
    public function __construct(BudgetSubCategoryRepository $budgetSubCategoryRepository)
    {
        $this->budgetSubCategory = $budgetSubCategoryRepository;
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(CreateSubCategoryRequest $request, int $id)
    {
        return $this->budgetSubCategory->create($request->validated(), $id);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @param  int  $subCategoryId
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateSubCategoryRequest $request, int $id, int $subCategoryId)
    {
        return $this->budgetSubCategory->update($request->validated(), $id, $subCategoryId);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(int $id, int $subCategoryId)
    {
        return $this->budgetSubCategory->delete($id, $subCategoryId);
    }
}
