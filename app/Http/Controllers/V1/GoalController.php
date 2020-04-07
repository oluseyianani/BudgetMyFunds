<?php

namespace App\Http\Controllers\V1;

use App\Models\Goal;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Repositories\V1\GoalRepository;
use App\Http\Requests\CreateGoalRequest;
use App\Http\Requests\UpdateGoalRequest;

class GoalController extends Controller
{
    protected $goal;
    public function __construct(GoalRepository $goalRepository)
    {
        $this->goal = $goalRepository;
    }

    /**
     *  @SWG\Get(
     *   path="/goal",
     *   description="Returns all Goals and trackings for a logged in user",
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
     *      @SWG\Parameter(
     *          name="user_id",
     *          description="The user id of the user you want to view (For superAdmins only)",
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
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $this->authorize('ownerSuperAdminAccess', $request->user());
        $begin = ($request->filled('begin')) ? $request->query('begin') : 0;
        $perPage = ($request->filled('per_page')) ? $request->query('per_page') : 25;
        $sortBy = ($request->filled('sort_by')) ? $request->query('sort_by') : "goal_created_on";
        $sortDirection = ($request->filled('sort_direction')) ? $request->query('sort_direction') : "asc";
        $optionalUserId = ($request->filled('user_id')) ? $request->query('user_id') : null;

        return $this->goal->fetchMany($begin, $perPage, $sortBy, $sortDirection, $optionalUserId);
    }

    /**
     * @SWG\Post(
     *    path="/goal",
     *    description="Creates a new goal",
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
     *          description="The title of the goal",
     *          required=true,
     *          in="body",
     *          @SWG\Schema(
     *              @SWG\Property(property="title", type="string", example="Example moving to Miami")
     *         )
     *      ),
     *     @SWG\Parameter(
     *          name="description",
     *          description="The description of the goal",
     *          required=true,
     *          in="body",
     *          @SWG\Schema(
     *              @SWG\Property(property="description", type="string", example="Here's an elaborate description of the goal")
     *         )
     *      ),
     *      @SWG\Parameter(
     *          name="amount",
     *          description="The amount to budget for the goal",
     *          required=true,
     *          in="body",
     *          @SWG\Schema(
     *              @SWG\Property(property="amount", type="decimal", example=5000000.90)
     *         )
     *      ),
     *       @SWG\Parameter(
     *          name="monthly_contributions",
     *          description="The amount to budget for the goal",
     *          required=true,
     *          in="body",
     *          @SWG\Schema(
     *              @SWG\Property(property="monthly_contributions", type="decimal", example=3000.00)
     *         )
     *      ),
     *     @SWG\Parameter(
     *          name="user_id",
     *          description="The id of the logged in user",
     *          required=true,
     *          in="body",
     *          @SWG\Schema(
     *              @SWG\Property(property="user_id", type="integer", example=101)
     *         )
     *      ),
     *        @SWG\Parameter(
     *          name="goal_categories_id",
     *          description="The id of the goal category",
     *          required=true,
     *          in="body",
     *          @SWG\Schema(
     *              @SWG\Property(property="goal_categories_id", type="integer", example=1)
     *         )
     *      ),
     *       @SWG\Parameter(
     *          name="due_date",
     *          description="The due date and time of the goal",
     *          required=true,
     *          in="body",
     *          @SWG\Schema(
     *              @SWG\Property(property="due_date", type="date", example="Y-m-d h:m:s")
     *         )
     *      ),
     *        @SWG\Parameter(
     *          name="auto_compute",
     *          description="Do you want to compute the trackings?",
     *          required=true,
     *          in="body",
     *          @SWG\Schema(
     *              @SWG\Property(property="auto_compute", type="boolean", example="0")
     *         )
     *      ),
     *     @SWG\Parameter(
     *          name="completed",
     *          description="completed goal?",
     *          required=false,
     *          in="body",
     *          @SWG\Schema(
     *              @SWG\Property(property="completed", type="boolean", example=0)
     *         )
     *      ),
     *    @SWG\Response(response=401, description="Invalid token"),
     *    @SWG\Response(response=201, description="goal created"),
     * )
     *
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(CreateGoalRequest $request)
    {
        return $this->goal->create($request->validated());
    }

    /**
     * @SWG\Get(
     *   path="/goal/{id}",
     *   description="Returns the specified goal.",
     *      @SWG\Parameter(
     *          name="Authorization",
     *          description="Bearer token, needed for authentication",
     *          required=true,
     *          type="string",
     *          in="header"
     *      ),
     *      @SWG\Parameter(
     *          name="id",
     *          description="The id of the goal to be returned.",
     *          required=true,
     *          type="integer",
     *          in="path"
     *      ),
     *       @SWG\Parameter(
     *          name="user_id",
     *          description="The id of user's goal to be returned. (For superAdmins only)",
     *          required=false,
     *          type="integer",
     *          in="query"
     *      ),
     *   @SWG\Response(response=401, description="Invalid token"),
     *   @SWG\Response(response=404, description="Category not found"),
     *   @SWG\Response(response=200, description="Ok"),
     * )
     * Display the specified resource.
     *
     * @param  \App\Models\Goal $id
     * @return \Illuminate\Http\Response
     */
    public function show(Request $request, Goal $id)
    {
        $this->authorize('view', $id);
        $optionalUserId = ($request->filled('user_id')) ? $request->query('user_id') : null;
        return $this->goal->fetchOne($id['id'], $optionalUserId);
    }

    /**
     * @SWG\Put(
     *   path="/goal/{id}",
     *   description="Updates the specified goal.",
     *      @SWG\Parameter(
     *          name="Authorization",
     *          description="Bearer token, needed for authentication",
     *          required=true,
     *          type="string",
     *          in="header"
     *      ),
     *      @SWG\Parameter(
     *          name="id",
     *          description="The id of the goal to be updated.",
     *          required=true,
     *          type="integer",
     *          in="path"
     *      ),
     *
     *      @SWG\Parameter(
     *          name="title",
     *          description="The title of the goal",
     *          required=true,
     *          type="string",
     *          in="body",
     *          @SWG\Schema(
     *              @SWG\Property(property="title", type="string", example="Example updated goal title")
     *         )
     *      ),
     *      @SWG\Parameter(
     *          name="description",
     *          description="The description of the goal",
     *          required=true,
     *          type="string",
     *          in="body",
     *          @SWG\Schema(
     *              @SWG\Property(property="description", type="string", example="Example updated goal description")
     *         )
     *      ),
     *      @SWG\Parameter(
     *          name="goal_categories_id",
     *          description="The goal category id of the goal",
     *          required=true,
     *          type="integer",
     *          in="body",
     *          @SWG\Schema(
     *              @SWG\Property(property="goal_categories_id", type="integer", example=1)
     *         )
     *      ),
     *   @SWG\Response(response=401, description="Invalid token"),
     *   @SWG\Response(response=404, description="Goal not found"),
     *   @SWG\Response(response=422, description="The given data was invalid."),
     *   @SWG\Response(response=200, description="Goal updated"),
     * )
     *
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Goal $id
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateGoalRequest $request, Goal $id)
    {
        $this->authorize('update', $id);
        return $this->goal->update($request->validated(), $id['id']);
    }

    /**
     * @SWG\Delete(
     *   path="/goal/{id}",
     *   description="Deletes the specified goal.",
     *      @SWG\Parameter(
     *          name="Authorization",
     *          description="Bearer token, needed for authentication",
     *          required=true,
     *          type="string",
     *          in="header"
     *      ),
     *      @SWG\Parameter(
     *          name="id",
     *          description="The id of the goal to be deleted.",
     *          required=true,
     *          type="integer",
     *          in="path"
     *      ),
     *   @SWG\Response(response=401, description="Invalid token"),
     *   @SWG\Response(response=404, description="Goal not found"),
     *   @SWG\Response(response=200, description="Goal deleted"),
     *   )
     *
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Goal $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Goal $id)
    {
        $this->authorize('delete', $id);
        return $this->goal->delete($id['id']);
    }

    public function complete()
    {
        //
    }
}
