<?php

namespace App\Repositories\V1;

use Exception;
use App\Models\Goal;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class GoalRepository extends BaseRepository
{

     /**
     * Gets all the Goal for a logged in user
     *
     * @param $begin
     * @param $perPage
     * @param $sortBy
     * @param $sortDirection
     *
     * @return json
     */
    public function fetchMany(int $begin, int $perPage, string $sortBy, string $sortDirection, $userId = null)
    {
        $userId = auth()->user()->isSuperAdmin() ? $userId : null;
        try {
            $goal = Goal::forLoggedInUser($userId)->orderBy($sortBy, $sortDirection)
                        ->with(['goalTrackings', 'goalCategory:id,title', 'totalContributions'])
                        ->offset($begin)
                        ->limit($perPage)
                        ->get();

            return formatResponse(200, 'Ok', true, collect($goal));
        } catch (Exception $e) {
            return formatResponse(fetchErrorCode($e), get_class($e) . ": " . $e->getMessage());
        }
    }

    /**
     * Creates category record
     *
     * @param array $data
     *
     * @return json
     */
    public function create(array $data)
    {
        try {
            $goal = Goal::create($data);

            return formatResponse(201, 'Goal created', true, collect($goal));
        } catch (Exception $e) {
            return formatResponse(fetchErrorCode($e), get_class($e) . ": " . $e->getMessage());
        }
    }

    /**
     * Fetches a category with its subcategories
     *
     * @param int $id
     *
     * @return json
     */
    public function fetchOne(int $id, $userId = null)
    {
        $userId = auth()->user()->isSuperAdmin() ? $userId : null;

        try {
            $goal = Goal::forLoggedInUser($userId)->with(['goalTrackings', 'totalContributions'])->findOrFail($id);

            return formatResponse(200, 'Ok', true, collect($goal));
        } catch (Exception $e) {
            if ($e instanceof ModelNotFoundException) {
                return formatResponse(404, 'Goal not found');
            }
            return formatResponse(fetchErrorCode($e), get_class($e) . ": " . $e->getMessage());
        }
    }

    /**
     * Updates a category resource
     *
     * @param array $data to update
     * @param int $id of the resource
     */
    public function update(array $data, int $id)
    {
        try {
            $goal = $this->getGoal($id);

            if (collect($goal)->isEmpty()) {
                return formatResponse(404, 'Goal not found', false);
            }

            $goal->title = $data['title'];
            $goal->description = $data['description'];
            $goal->goal_categories_id = $data['goal_categories_id'];

            if ($goal->isDirty()) {
                $goal->save();
                return formatResponse(200, 'Goal updated', true, collect($goal));
            }

            return formatResponse(200, 'No changes made. No update required.', true, collect($goal));
        } catch (Exception $e) {
            return formatResponse(fetchErrorCode($e), get_class($e) . ": " . $e->getMessage());
        }
    }

    /**
     * Destroys a category resource
     *
     * @param int $id of the category resource to destroy
     */
    public function delete(int $id)
    {
        try {
            $goal = $this->getGoal($id);

            if (collect($goal)->isEmpty()) {
                return formatResponse(404, 'Goal not found', false);
            }

            $goal->delete();
            return formatResponse(200, 'Goal deleted', true);
        } catch (Exception $e) {
            return formatResponse(fetchErrorCode($e), get_class($e) . ": ". $e->getMessage());
        }
    }

    /**
     * Get the goal for this id
     *
     * @param int $id
     */
    public function getGoal(int $id)
    {
        return Goal::where('id', $id)->first();
    }
}
