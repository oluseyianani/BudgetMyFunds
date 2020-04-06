<?php

namespace App\Repositories\V1;

use Exception;
use App\Models\Goal;
use App\Models\GoalCategory;
use App\Models\GoalTracking;
use App\Repositories\V1\BaseRepository;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class GoalTrackingRepository extends BaseRepository
{

     /**
     * Gets all the Goal tracking
     *
     * @param $begin
     * @param $perPage
     * @param $sortBy
     * @param $sortDirection
     *
     * @return json
     */
    public function fetchMany(int $begin, int $perPage, string $sortBy, string $sortDirection)
    {
        //
    }

    /**
     * Creates goal tracking record
     *
     * @param array $data
     * @param int $id
     *
     * @return json
     */
    public function create(array $data, $id = null)
    {
        try {
            $goal = Goal::unfinishedGoal()->findOrFail($id);

            $goalTracking = new GoalTracking();
            $goalTracking->goal_id = $goal['id'];
            $goalTracking->amount_contributed = $data['amount_contributed'];
            $goalTracking->save();

            return formatResponse(201, 'Goal tracking created', true, collect($goalTracking));
        } catch (Exception $e) {
            if ($e instanceof ModelNotFoundException) {
                return formatResponse(404, 'unfinished goal was not found');
            }
            return formatResponse(fetchErrorCode($e), get_class($e) . ": ". $e->getMessage());
        }
    }

    /**
     * Fetches a goal tracking
     *
     * @param int $id
     *
     * @return json
     */
    public function fetchOne(int $id)
    {
        //
    }

    /**
     * Updates a goal tracking resource
     *
     * @param array $data to update
     * @param int $id of the resource
     * @param int $trackingId of the resource
     */
    public function update(array $data, int $id, $trackingId = null)
    {
        try {
            $goalTracking = GoalTracking::findOrFail($trackingId);

            $goalTracking->amount_contributed = $data['amount_contributed'];

            if (isset($goalTracking['date_contributed'])) {
                $goalTracking->date_contributed = $data['date_contributed'];
            }

            $goalTracking->update();

            return formatResponse(200, 'goal tracking updated', true, collect($goalTracking));
        } catch (Exception $e) {
            if ($e instanceof ModelNotFoundException) {
                return formatResponse(404, 'unfinished goal was not found');
            }
            return formatResponse(fetchErrorCode($e), get_class($e) . ": ". $e->getMessage());
        }
    }

    /**
     * Destroys a goal tracking resource
     *
     * @param int $id of the goal category resource to destroy
     * @param int $trackingId of the goal category resource to destroy
     */
    public function delete(int $id, int $trackingId = null)
    {
        try {
            $goalTracking = GoalTracking::findOrFail($trackingId);

            $goalTracking->delete();
            return formatResponse(200, 'Goal tracking deleted', true);
        } catch (Exception $e) {
            if ($e instanceof ModelNotFoundException) {
                return formatResponse(404, 'unfinished goal was not found');
            }
            return formatResponse(fetchErrorCode($e), get_class($e) . ": ". $e->getMessage());
        }
    }
}
