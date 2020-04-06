<?php

namespace App\Repositories\V1;

use Exception;
use App\Models\GoalCategory;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class GoalCategoryRepository extends BaseRepository
{

     /**
     * Gets all the Goal Category
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
        try {
            $goalCategories = GoalCategory::orderBy($sortBy, $sortDirection)
                                ->offset($begin)
                                ->limit($perPage)
                                ->get();

            return formatResponse(200, 'Ok', true, collect($goalCategories));
        } catch (Exception $e) {
            return formatResponse(fetchErrorCode($e), get_class($e) . ": " . $e->getMessage());
        }
    }

    /**
     * Creates goal category record
     *
     * @param array $data
     *
     * @return json
     */
    public function create(array $data)
    {
        try {
            $goalCategory = GoalCategory::create($data);

            return formatResponse(201, 'Goal Category created', true, collect($goalCategory));
        } catch (Exception $e) {
            return formatResponse(fetchErrorCode($e), get_class($e) . ": " . $e->getMessage());
        }
    }

    /**
     * Fetches a goal category
     *
     * @param int $id
     *
     * @return json
     */
    public function fetchOne(int $id)
    {
        try {
            $goalCategory = GoalCategory::findOrFail($id);

            return formatResponse(200, 'Ok', true, collect($goalCategory));
        } catch (Exception $e) {
            if ($e instanceof ModelNotFoundException) {
                return formatResponse(404, 'Goal Category not found');
            }
            return formatResponse(fetchErrorCode($e), get_class($e) . ": " . $e->getMessage());
        }
    }

    /**
     * Updates a goal category resource
     *
     * @param array $data to update
     * @param int $id of the resource
     */
    public function update(array $data, int $id)
    {
        try {
            $goalCategories = $this->getGoalCategory($id);

            if (collect($goalCategories)->isEmpty()) {
                return formatResponse(404, 'Goal category not found', false);
            }

            $goalCategories['title'] = $data['title'];

            if ($goalCategories->isDirty()) {
                $goalCategories->save();

                return formatResponse(200, 'Goal category updated', true, collect($goalCategories));
            }

            return formatResponse(200, 'No changes made. No update required.', true, collect($goalCategories));
        } catch (Exception $e) {
            return formatResponse(fetchErrorCode($e), get_class($e) . ": " . $e->getMessage());
        }
    }

    /**
     * Destroys a goal category resource
     *
     * @param int $id of the goal category resource to destroy
     */
    public function delete(int $id)
    {
        try {
            $goalCategories = $this->getGoalCategory($id);

            if (collect($goalCategories)->isEmpty()) {
                return formatResponse(404, 'Goal category not found', false);
            }

            $goalCategories->delete();
            return formatResponse(200, 'Goal category deleted', true);
        } catch (Exception $e) {
            return formatResponse(fetchErrorCode($e), get_class($e) . ": " . $e->getMessage());
        }
    }

    /**
     * Gets a goal category
     * @param int $id
     */
    public function getGoalCategory(int $id)
    {
        return GoalCategory::where('id', $id)->first();
    }
}
