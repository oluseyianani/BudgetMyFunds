<?php

namespace App\Http\Controllers\V1;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Repositories\V1\GoalCategoryRepository;
use App\Http\Requests\CreateGoalCategoryRequest;
use App\Http\Requests\UpdateGoalCategoryRequest;

class GoalCategoryController extends Controller
{
    protected $goalCategoryRepository;
    public function __construct(GoalCategoryRepository $goalCategoryRepository)
    {
        $this->goalCategory = $goalCategoryRepository;
    }

    /**
     * @SWG\Get(
     *   path="/goal/category",
     *   description="Returns all Goals Category",
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
     *   @SWG\Response(response=401, description="Invalid token"),
     *   @SWG\Response(response=200, description="Ok"),
     * )
     *
     * Display a listing of the resource.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $this->authorize('view', $request->user());
        $begin = ($request->filled('begin')) ? $request->query('begin') : 0;
        $perPage = ($request->filled('per_page')) ? $request->query('per_page') : 25;
        $sortBy = ($request->filled('sort_by')) ? $request->query('sort_by') : "title";
        $sortDirection = ($request->filled('sort_direction')) ? $request->query('sort_direction') : "asc";

        return $this->goalCategory->fetchMany($begin, $perPage, $sortBy, $sortDirection);
    }

    /**
     * @SWG\Post(
     *    path="/goal/category",
     *    description="Creates a new goal category",
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
     *    @SWG\Response(response=401, description="Invalid token"),
     *    @SWG\Response(response=201, description="Goal Category created"),
     *
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(CreateGoalCategoryRequest $request)
    {
        $this->authorize('view', $request->user());
        return $this->goalCategory->create($request->validated());
    }

    /**
     *  * @SWG\Get(
     *   path="/goal/category/{id}",
     *   description="Returns the specified goal category.",
     *      @SWG\Parameter(
     *          name="Authorization",
     *          description="Bearer token, needed for authentication",
     *          required=true,
     *          type="string",
     *          in="header"
     *      ),
     *      @SWG\Parameter(
     *          name="id",
     *          description="The id of the goal category to be returned.",
     *          required=true,
     *          type="integer",
     *          in="path"
     *      ),
     *   @SWG\Response(response=401, description="Invalid token"),
     *   @SWG\Response(response=404, description="Goal Category not found"),
     *   @SWG\Response(response=200, description="Ok"),
     * )
     *
     * Display the specified resource.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function show(Request $request, int $id)
    {
        $this->authorize('view', $request->user());
        return $this->goalCategory->fetchOne($id);
    }

    /**
     * @SWG\Put(
     *   path="/goal/category/{id}",
     *   description="Updates the specified goal category.",
     *      @SWG\Parameter(
     *          name="Authorization",
     *          description="Bearer token, needed for authentication",
     *          required=true,
     *          type="string",
     *          in="header"
     *      ),
     *      @SWG\Parameter(
     *          name="id",
     *          description="The id of the goal category to be updated.",
     *          required=true,
     *          type="integer",
     *          in="path"
     *      ),
     *
     *      @SWG\Parameter(
     *          name="title",
     *          description="The title of the goal category",
     *          required=true,
     *          type="string",
     *          in="body",
     *          @SWG\Schema(
     *              @SWG\Property(property="title", type="string", example="Fundings")
     *         )
     *      ),
     *   @SWG\Response(response=401, description="Invalid token"),
     *   @SWG\Response(response=404, description="Goal category not found"),
     *   @SWG\Response(response=422, description="The given data was invalid."),
     *   @SWG\Response(response=200, description="Category updated"),
     * )
     *
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $goalCategory
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateGoalCategoryRequest $request, int $id)
    {
        $this->authorize('view', $request->user());
        return $this->goalCategory->update($request->validated(), $id);
    }

    /**
     * @SWG\Delete(
     *   path="/goal/category/{id}",
     *   description="Deletes the specified goal category.",
     *      @SWG\Parameter(
     *          name="Authorization",
     *          description="Bearer token, needed for authentication",
     *          required=true,
     *          type="string",
     *          in="header"
     *      ),
     *      @SWG\Parameter(
     *          name="id",
     *          description="The id of the goal category to be deleted.",
     *          required=true,
     *          type="integer",
     *          in="path"
     *      ),
     *
     *   @SWG\Response(response=401, description="Invalid token"),
     *   @SWG\Response(response=404, description="Goal category not found"),
     *   @SWG\Response(response=200, description="Goal category deleted"),
     *  )
     *
     * Remove the specified resource from storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request, int $id)
    {
        $this->authorize('view', $request->user());
        return $this->goalCategory->delete($id);
    }
}
