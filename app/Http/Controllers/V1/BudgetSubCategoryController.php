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
     *  @SWG\Post(
     *    path="/category/{id}/subcategory",
     *    description="Creates a new sub category",
     *    consumes={"multipart/form-data", "application/json"},
     *       @SWG\Parameter(
     *          name="Authorization",
     *          description="Bearer token, needed for authentication",
     *          required=true,
     *          type="string",
     *          in="header"
     *        ),
     *       @SWG\Parameter(
     *          name="id",
     *          description="The id of the category to be returned.",
     *          required=true,
     *          type="integer",
     *          in="path"
     *      ),
     *      @SWG\Parameter(
     *          name="sub_title",
     *          description="The sub-title of the subCategory",
     *          required=true,
     *          in="body",
     *          @SWG\Schema(
     *              @SWG\Property(property="sub_title", type="string", example="savings")
     *         )
     *      ),
     *    @SWG\Response(response=401, description="Invalid token"),
     *    @SWG\Response(response=404, description="Category not found"),
     *    @SWG\Response(response=201, description="SubCategory Created"),
     * )
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(CreateSubCategoryRequest $request, int $id)
    {
        $this->authorize('create', $request->user());
        return $this->budgetSubCategory->create($request->validated(), $id);
    }

    /**
     *  @SWG\Put(
     *    path="/category/{id}/subcategory/{subcategoryId}",
     *    description="Edits a sub category",
     *    consumes={"multipart/form-data", "application/json"},
     *       @SWG\Parameter(
     *          name="Authorization",
     *          description="Bearer token, needed for authentication",
     *          required=true,
     *          type="string",
     *          in="header"
     *        ),
     *       @SWG\Parameter(
     *          name="id",
     *          description="The id of the category to be returned.",
     *          required=true,
     *          type="integer",
     *          in="path"
     *      ),
     *       @SWG\Parameter(
     *          name="subcategoryId",
     *          description="The id of the sub-category to be updated.",
     *          required=true,
     *          type="integer",
     *          in="path"
     *      ),
     *      @SWG\Parameter(
     *          name="sub_title",
     *          description="The sub-title of the subCategory",
     *          required=true,
     *          in="body",
     *          @SWG\Schema(
     *              @SWG\Property(property="sub_title", type="string", example="savings updated")
     *         )
     *      ),
     *    @SWG\Response(response=401, description="Invalid token"),
     *    @SWG\Response(response=404, description="Category or SubCategory not found"),
     *    @SWG\Response(response=200, description="SubCategory Updated"),
     * )
     *
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @param  int  $subCategoryId
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateSubCategoryRequest $request, int $id, int $subCategoryId)
    {
        $this->authorize('update', $request->user());
        return $this->budgetSubCategory->update($request->validated(), $id, $subCategoryId);
    }

    /**
     *  @SWG\Delete(
     *    path="/category/{id}/subcategory/{subcategoryId}",
     *    description="Edits a sub category",
     *    consumes={"multipart/form-data", "application/json"},
     *       @SWG\Parameter(
     *          name="Authorization",
     *          description="Bearer token, needed for authentication",
     *          required=true,
     *          type="string",
     *          in="header"
     *        ),
     *       @SWG\Parameter(
     *          name="id",
     *          description="The id of the category to be returned.",
     *          required=true,
     *          type="integer",
     *          in="path"
     *      ),
     *       @SWG\Parameter(
     *          name="subcategoryId",
     *          description="The id of the sub-category to be deleted.",
     *          required=true,
     *          type="integer",
     *          in="path"
     *      ),
     *    @SWG\Response(response=401, description="Invalid token"),
     *    @SWG\Response(response=404, description="Category or SubCategory not found"),
     *    @SWG\Response(response=200, description="SubCategory deleted"),
     * )
     *
     *
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request, int $id, int $subCategoryId)
    {
        $this->authorize('delete', $request->user());
        return $this->budgetSubCategory->delete($id, $subCategoryId);
    }
}
