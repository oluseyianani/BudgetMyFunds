<?php

namespace App\Http\Controllers\V1;

use App\Models\Goal;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Repositories\V1\GoalTrackingRepository;
use App\Http\Requests\CreateGoalTrackingRequest;
use App\Http\Requests\UpdateGoalTrackingRequest;

class GoalTrackingController extends Controller
{
    protected $goalTracking;
    public function __construct(GoalTrackingRepository $goalTrackingRepository)
    {
        $this->goalTracking = $goalTrackingRepository;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * @SWG\Post(
     *    path="/goal/{id}/tracking",
     *    description="Creates payment trackings for a goal",
     *    consumes={"multipart/form-data", "application/json"},
     *       @SWG\Parameter(
     *          name="Authorization",
     *          description="Bearer token, needed for authentication",
     *          required=true,
     *          type="string",
     *          in="header"
     *        ),
     *      @SWG\Parameter(
     *          name="goal_id",
     *          description="The id of the goaly",
     *          required=true,
     *          in="body",
     *          @SWG\Schema(
     *              @SWG\Property(property="goal_id", type="integer", example=1)
     *         )
     *      ),
     *     @SWG\Parameter(
     *          name="amount_contributed",
     *          description="The amount contributed for the goal",
     *          required=true,
     *          in="body",
     *          @SWG\Schema(
     *              @SWG\Property(property="amount_contributed", type="decimal", example=100.90)
     *         )
     *      ),
     *    @SWG\Response(response=401, description="Invalid token"),
     *    @SWG\Response(response=201, description="Goal tracking created"),
     * )
     *
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(CreateGoalTrackingRequest $request, Goal $id)
    {
        $this->authorize('create', $id);
        return $this->goalTracking->create($request->validated(), $id['id']);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Goal  $goal
     * @return \Illuminate\Http\Response
     */
    public function show(Goal $goal)
    {
       //
    }

    /**
     * @SWG\Put(
     *   path="/goal/{id}/tracking/{trackingId}",
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
     *          description="The id of the goal to be updated.",
     *          required=true,
     *          type="integer",
     *          in="path"
     *      ),
     *      @SWG\Parameter(
     *          name="trackingId",
     *          description="The goal tracking id of the goal to be updated.",
     *          required=true,
     *          type="integer",
     *          in="path"
     *      ),
     *      @SWG\Parameter(
     *          name="amount_contributed",
     *          description="The amount contributed for this goal",
     *          required=true,
     *          type="string",
     *          in="body",
     *          @SWG\Schema(
     *              @SWG\Property(property="amount_contributed", type="decimal", example=1005.00)
     *         )
     *      ),
     *       @SWG\Parameter(
     *          name="date_contributed",
     *          description="The amount contributed for this goal",
     *          required=false,
     *          type="date",
     *          in="body",
     *          @SWG\Schema(
     *              @SWG\Property(property="date_contributed", type="date", example="Y-m-d h:m:s" )
     *         )
     *      ),
     *   @SWG\Response(response=401, description="Invalid token"),
     *   @SWG\Response(response=404, description="Goal tracking not found"),
     *   @SWG\Response(response=422, description="The given data was invalid."),
     *   @SWG\Response(response=200, description="Goal tracking updated"),
     * )
     *
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\GoalTracking  $goalTracking
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateGoalTrackingRequest $request, Goal $id, int $trackingId)
    {
        $this->authorize('update', $id);
        return $this->goalTracking->update($request->validated(), $id['id'], $trackingId);
    }

    /**
     * @SWG\Delete(
     *   path="/goal/{id}/tracking/{trackingId}",
     *   description="Deletes the specified goal tracking.",
     *      @SWG\Parameter(
     *          name="Authorization",
     *          description="Bearer token, needed for authentication",
     *          required=true,
     *          type="string",
     *          in="header"
     *      ),
     *      @SWG\Parameter(
     *          name="id",
     *          description="The id of the goal tracking to be deleted.",
     *          required=true,
     *          type="integer",
     *          in="path"
     *      ),
     *
     *      @SWG\Parameter(
     *          name="trackingId",
     *          description="The tracking id of the goal tracking to be deleted.",
     *          required=true,
     *          type="integer",
     *          in="path"
     *      ),
     *
     *   @SWG\Response(response=401, description="Invalid token"),
     *   @SWG\Response(response=404, description="Goal tracking not found"),
     *   @SWG\Response(response=200, description="Goal tracking deleted"),
     *   )
     *
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Goal $id
     * @param  int $trackingId
     * @return \Illuminate\Http\Response
     */
    public function destroy(Goal $id, $trackingId)
    {
        $this->authorize('delete', $id);
        return $this->goalTracking->delete($id['id'], $trackingId);
    }
}
