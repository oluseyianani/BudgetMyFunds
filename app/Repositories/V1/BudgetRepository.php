<?php

namespace App\Repositories\V1;

use Exception;
use App\Models\Budget;
use App\Http\Resources\BudgetResource;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class BudgetRepository extends BaseRepository
{

    /**
    * Gets all the categories
    *
    * @param $begin
    * @param $perPage
    * @param $sortBy
    * @param $sortDirection
    * @param $year
    * @param $month
    * @param $userId
    * @return json
    */
    public function fetchMany(int $begin, int $perPage, string $sortBy, string $sortDirection, string $year = null, string $month = null, int $userId = null)
    {
        $userId = auth()->user()->isSuperAdmin() ? $userId : null;
        $budgetData;
        try {
            if ($year && $month) {
                $budgetData = Budget::forLoggedInUser($userId)->whereMonth('budget_for_month', $month)
                                ->whereYear('budget_for_month', $year)
                                ->with(["category:id,title", "subCategory:id,sub_title"])
                                ->offset($begin)
                                ->limit($perPage)
                                ->get();
            } else {
                $budgetData = Budget::forLoggedInUser($userId)->orderBy($sortBy, $sortDirection)
                                ->with(["category:id,title", "subCategory:id,sub_title"])
                                ->offset($begin)
                                ->limit($perPage)
                                ->get();
            }

            return formatResponse(200, 'Ok', true, $budgetData);
        } catch (Exception $e) {
            return formatResponse(fetchErrorCode($e), get_class($e) . ": " . $e->getMessage());
        }
    }


    /**
     * Creates Budget record
     *
     * @param array $data
     *
     * @return json
     */
    public function create(array $data)
    {
        try {
            $data = Budget::create($data);

            return formatResponse(201, 'Budget created', true, $data);
        } catch (Exception $e) {
            return formatResponse(fetchErrorCode($e), get_class($e) . ": " . $e->getMessage());
        }
    }

    /**
     * Fetches a budegt for a loggedin user with its categories and subcategories
     *
     * @param int $id
     * @param int $userId
     *
     * @return json
     */
    public function fetchOne(int $id, int $userId = null)
    {
        try {
            $budgetData = Budget::forLoggedInUser($userId)->with(["category:id,title", "subCategory:id,sub_title"])->findOrFail($id);

            return formatResponse(200, 'Ok', true, collect($budgetData));
        } catch (Exception $e) {
            if ($e instanceof ModelNotFoundException) {
                return formatResponse(404, 'Budget not found');
            }
            return formatResponse(fetchErrorCode($e), get_class($e) . ": " . $e->getMessage());
        }
    }

    /**
     * Updates a budget resource
     *
     * @param array $data to update
     * @param int $id of the resource
     *
     * @return json
     */
    public function update(array $data, int $id)
    {
        try {
            $budget = $this->getBudget($id);

            $budget['title'] = $data['title'];
            $budget['category_id'] = $data['category_id'];

            if (isset($data['sub_category_id'])) {
                $budget['sub_category_id'] = $data['sub_category_id'];
            }

            if (!isset($data['sub_category_id'])) {
                $budget['sub_category_id'] = null;
            }

            $budget['dedicated_amount'] = $data['dedicated_amount'];
            $budget['budget_for_month'] = $data['budget_for_month'];

            if ($budget->isDirty()) {
                $budget->save();
                return formatResponse(200, 'Budget updated', true, collect($budget));
            }

            return formatResponse(200, 'No changes made. No update required.', true, collect($budget));
        } catch (Exception $e) {
            if ($e instanceof ModelNotFoundException) {
                return formatResponse(404, 'Budget not found');
            }
            return formatResponse(fetchErrorCode($e), get_class($e) . ": " . $e->getMessage());
        }
    }

    /**
     * Destroys a budget resource
     *
     * @param int $id of the budget resource to destroy
     */
    public function delete(int $id)
    {
        try {
            $budget = $this->getBudget($id);

            $budget->delete();
            return formatResponse(200, 'Budget Deleted', true);
        } catch (Exception $e) {
            if ($e instanceof ModelNotFoundException) {
                return formatResponse(404, 'Budget not found');
            }
            return formatResponse(fetchErrorCode($e), get_class($e) . ": " . $e->getMessage());
        }
    }

    public function getBudget($id)
    {
        return Budget::forLoggedInUser()->findOrFail($id);
    }
}
