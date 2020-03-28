<?php

namespace App\Http\Controllers\V1;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Requests\CreateCategoryRequest;
use App\Http\Requests\UpdateCategoryRequest;
use App\Repositories\V1\BudgetCategoryRepository;

class BudgetCategoryController extends Controller
{

    protected $budgetCategory;

    public function __construct(BudgetCategoryRepository $budgetCategoryRepository)
    {
        $this->budgetCategory = $budgetCategoryRepository;
    }

    /**
     *   @SWG\Get(
     *   path="/category",
     *   description="Gets all categories",
     *   consumes={"multipart/form-data", "application/json"},
     *       @SWG\Parameter(
     *          name="Authorization",
     *          description="Bearer token, needed for authentication",
     *          required=true,
     *          type="string",
     *          in="header"
     *      ),
     *      @SWG\Parameter(
     *          name="per_page",
     *          description="The number of categories to return in a list or page.",
     *          required=false,
     *          type="integer",
     *          in="query"
     *      ),
     *      @SWG\Parameter(
     *          name="begin",
     *          description="The offset to start fetching results from.",
     *          required=false,
     *          type="integer",
     *          in="query"
     *      ),
     *      @SWG\Parameter(
     *          name="sort_by",
     *          description="The column to sort results with. The default is title.",
     *          required=false,
     *          type="string",
     *          in="query"
     *      ),
     *      @SWG\Parameter(
     *          name="sort_direction",
     *          description="The direction for sorting, either asc (asceding) or desc (descending). Default is ascending",
     *          required=false,
     *          type="string",
     *          in="query"
     *      ),
     *
     *   @SWG\Response(response=401, description="Invalid token"),
     *   @SWG\Response(response=200, description="OK"),
     * )
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $this->authorize('view', $request->user());
        $begin = ($request->filled('begin')) ? $request->query('begin') : 0;
        $perPage = ($request->filled('per_page')) ? $request->query('per_page') : 25;
        $sortBy = ($request->filled('sort_by')) ? $request->query('sort_by') : "title";
        $sortDirection = ($request->filled('sort_direction')) ? $request->query('sort_direction') : "asc";

        return $this->budgetCategory->fetchMany($begin, $perPage, $sortBy, $sortDirection);
    }

    /**
     * @SWG\Post(
     *    path="/category",
     *    description="Creates a new category",
     *    consumes={"multipart/form-data", "application/json"},
     *       @SWG\Parameter(
     *          name="Authorization",
     *          description="Bearer token, needed for authentication",
     *          required=true,
     *          type="string",
     *          in="header"
     *        ),
     *      @SWG\Parameter(
     *          name="title",
     *          description="The title of the category",
     *          required=true,
     *          in="body",
     *          @SWG\Schema(
     *              @SWG\Property(property="title", type="string", example="Bills & Funds")
     *         )
     *      ),
     *     @SWG\Parameter(
     *          name="creator",
     *          description="The creator of the category",
     *          required=true,
     *          in="body",
     *          @SWG\Schema(
     *              @SWG\Property(property="creator", type="integer", example=1)
     *         )
     *      ),
     *    @SWG\Response(response=401, description="Invalid token"),
     *    @SWG\Response(response=201, description="Category created"),
     * )
     *
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(CreateCategoryRequest $request)
    {
        $this->authorize('create', $request->user());
        return $this->budgetCategory->create($request->validated());
    }

    /**
     * @SWG\Get(
     *   path="/category/{id}",
     *   description="Returns the specified category.",
     *      @SWG\Parameter(
     *          name="Authorization",
     *          description="Bearer token, needed for authentication",
     *          required=true,
     *          type="string",
     *          in="header"
     *      ),
     *      @SWG\Parameter(
     *          name="id",
     *          description="The id of the category to be returned.",
     *          required=true,
     *          type="integer",
     *          in="path"
     *      ),
     *   @SWG\Response(response=401, description="Invalid token"),
     *   @SWG\Response(response=404, description="Category not found"),
     *   @SWG\Response(response=200, description="Ok"),
     * )
     *
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(Request $request, int $id)
    {
        $this->authorize('view', $request->user());
        return $this->budgetCategory->fetchOne($id);
    }


    /**
     * @SWG\Put(
     *   path="/category/{id}",
     *   description="Updates the specified category.",
     *      @SWG\Parameter(
     *          name="Authorization",
     *          description="Bearer token, needed for authentication",
     *          required=true,
     *          type="string",
     *          in="header"
     *      ),
     *      @SWG\Parameter(
     *          name="id",
     *          description="The id of the category to be updated.",
     *          required=true,
     *          type="integer",
     *          in="path"
     *      ),
     *
     *      @SWG\Parameter(
     *          name="title",
     *          description="The title of the category",
     *          required=true,
     *          type="string",
     *          in="body",
     *          @SWG\Schema(
     *              @SWG\Property(property="title", type="string", example="Fundings")
     *         )
     *      ),
     *   @SWG\Response(response=401, description="Invalid token"),
     *   @SWG\Response(response=404, description="Category not found"),
     *   @SWG\Response(response=422, description="The given data was invalid."),
     *   @SWG\Response(response=200, description="Category updated"),
     * )
     *
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateCategoryRequest $request, int $id)
    {
        $this->authorize('update', $request->user());
        return $this->budgetCategory->update($request->validated(), $id);
    }

    /**
     * @SWG\Delete(
     *   path="/category/{id}",
     *   description="Deletes the specified category.",
     *      @SWG\Parameter(
     *          name="Authorization",
     *          description="Bearer token, needed for authentication",
     *          required=true,
     *          type="string",
     *          in="header"
     *      ),
     *      @SWG\Parameter(
     *          name="id",
     *          description="The id of the category to be deleted.",
     *          required=true,
     *          type="integer",
     *          in="path"
     *      ),
     *
     *      @SWG\Parameter(
     *          name="memberId",
     *          description="The id of the account member to be deleted.",
     *          required=true,
     *          type="integer",
     *          in="path"
     *      ),
     *
     *   @SWG\Response(response=401, description="Invalid token"),
     *   @SWG\Response(response=404, description="Category not found"),
     *   @SWG\Response(response=200, description="Category deleted"),
     *   )
     *
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request, int $id)
    {
        $this->authorize('delete', $request->user());
        return $this->budgetCategory->delete($id);
    }
}
