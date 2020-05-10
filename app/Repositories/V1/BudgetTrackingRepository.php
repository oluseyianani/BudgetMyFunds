<?php

namespace App\Repositories\V1;

use Exception;
use App\Models\Budget;
use App\Models\BudgetTracking;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class BudgetTrackingRepository extends BaseRepository
{
    /**
    * Gets all the budget expense tracking
    *
    * @param $begin
    * @param $perPage
    * @param $sortBy
    * @param $sortDirection
    * @param $year
    * @param $month
    * @param $userId
    * @param $id
    * @return json
    */
    public function fetchMany(int $begin, int $perPage, string $sortBy, string $sortDirection, string $year = null, string $month = null, int $userId = null, $id = null)
    {
        try {
            $budgetTrackingData = (new Budget())->forLoggedInUser($userId)
                                    ->findOrFail($id)
                                    ->budgetTracking()
                                    ->orderBy($sortBy, $sortDirection)
                                    ->offset($begin)
                                    ->limit($perPage)
                                    ->get();

            return formatResponse(200, 'Ok', true, $budgetTrackingData);
        } catch (Exception $e) {
            if ($e instanceof ModelNotFoundException) {
                return formatResponse(404, 'Budget not found');
            }
            return formatResponse(fetchErrorCode($e), get_class($e) . ": " . $e->getMessage());
        }
    }

    /**
     *  Create Budget expence tracking record
     * @param array $data
     * @param int $id
     *
     * @return json
     */
    public function create(array $data, int $id = null)
    {
        try {
            $budgetTracking = (new Budget())->forLoggedInUser()
                                ->findOrFail($id)
                                ->budgetTracking()
                                ->save(new BudgetTracking($data));

            if ($budgetTracking) {
                return formatResponse(201, 'Budget expense tracking created', true, collect($budgetTracking));
            }
        } catch (Exception $e) {
            if ($e instanceof ModelNotFoundException) {
                return formatResponse(404, 'Budget not found');
            }
            return formatResponse(fetchErrorCode($e), get_class($e) . ": " . $e->getMessage());
        }
    }

    /**
     * Updates a budget expense tracking resource
     *
     * @param array $data to update
     * @param int $id of the budget
     * @param int $budgetTrackingId
     *
     * @return json
     */
    public function fetchOne(int $id, int $budgetTrackingId = null)
    {
        try {
            $budgetTracking = (new Budget())->forLoggedInUser()
                                ->findOrFail($id)
                                ->budgetTracking()
                                ->findOrFail($budgetTrackingId);

            if ($budgetTracking) {
                return formatResponse(200, 'Ok', true, $budgetTracking);
            }
        } catch (Exception $e) {
            if ($e instanceof ModelNotFoundException) {
                return formatResponse(404, 'Budget or Budget expenses not found');
            }
            return formatResponse(fetchErrorCode($e), get_class($e) . ": " . $e->getMessage());
        }
    }

    /**
     * Updates a budget expense tracking resource
     *
     * @param array $data to update
     * @param int $id of the resource
     * @param int $budgetTrackingId of the resource
     *
     * @return json
     */
    public function update(array $data, int $id, int $budgetTrackingId = null)
    {
        try {
            $budgetTracking = (new Budget())->forLoggedInUser()
                                ->findOrFail($id)
                                ->budgetTracking()->findOrFail($budgetTrackingId)
                                ->update($data);

            if ($budgetTracking) {
                return formatResponse(200, 'Budget expenses updated', true, $this->getBudgetExpense($id, $budgetTrackingId));
            }
        } catch (Exception $e) {
            if ($e instanceof ModelNotFoundException) {
                return formatResponse(404, 'Budget or Budget expenses not found');
            }
            return formatResponse(fetchErrorCode($e), get_class($e) . ": " . $e->getMessage());
        }
    }

    /**
     * Destroys a budget expense tracking resource
     *
     * @param int $id of the budget resource to destroy
     * @param int $budgetTrackingId
     *
     * @return json
     */
    public function delete(int $id, int $budgetTrackingId = null)
    {
        try {
            $isDeletedBudgetTracking = (new Budget())->forLoggedInUser()
                                        ->findOrFail($id)
                                        ->budgetTracking()->findOrFail($budgetTrackingId)
                                        ->delete();
            if ($isDeletedBudgetTracking) {
                return formatResponse(200, 'Budget expenses deleted', true);
            }
        } catch (Exception $e) {
            if ($e instanceof ModelNotFoundException) {
                return formatResponse(404, 'Budget or Budget expenses not found');
            }
            return formatResponse(fetchErrorCode($e), get_class($e) . ": " . $e->getMessage());
        }
    }

    /**
     * Gets a collection of budget expense tracking resource
     *
     * @param int $id of the budget resource to destroy
     * @param int $budgetTrackingId
     *
     * @return collection
     */
    public function getBudgetExpense($budgetId, $budgetTrackingId)
    {
        return BudgetTracking::where(['id' => $budgetTrackingId, 'budget_id' => $budgetId])->get();
    }
}
