<?php

namespace App\Repositories\V1;

use Exception;
use App\Models\UserCategory;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class BudgetUserCategoryRepository extends BaseRepository
{

    /**
     * Gets all the subcategories
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
     * Creates subcategory record
     *
     * @param array $data
     * @param int $id
     *
     * @return json
     */
    public function create(array $data, int $categoryId = null)
    {
        try {

            $userCategory = UserCategory::create(array_merge(['user_id' => request()->user()['id']], $data, ['category_id' => $categoryId]));

            if ($userCategory) {
                return formatResponse(201, 'created', true, collect($userCategory));
            }

        } catch (Exception $e) {
            if ($e instanceof ModelNotFoundException) {
                return formatResponse(404, 'Category not found');
            }
            return formatResponse(fetchErrorCode($e), get_class($e) . ": " . $e->getMessage());
        }
    }

    /**
     * Fetches a subcategory
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
     * Updates a subcategory resource
     *
     * @param array $data to update
     * @param int $id of the resource
     * @param int $userCategoryId of the resource
     */
    public function update(array $data, int $id, int $userCategoryId= null)
    {
       try {
            $userCategory = $this->getUserCategory($userCategoryId, $id, request()->user()['id']);

            if (collect($userCategory)->isEmpty()) {
                return formatResponse(404, 'user category not found', false);
            }

            $userCategory['title'] = $data['title'];

            if ($userCategory->isDirty()) {
                $userCategory->save();

                return formatResponse(200, 'user category updated', true, collect($userCategory));
            }

            return formatResponse(200, 'No changes made. No update required.', true, collect($userCategory));

       } catch (Exception $e) {
            return formatResponse(fetchErrorCode($e), get_class($e) . ": " . $e->getMessage());
       }
    }

    /**
     * Destroys a subcategory resource
     *
     * @param int $id of the category resource to destroy
     * @param int $userCategoryId of the usercategory resource to destroy
     */
    public function delete(int $id, int $userCategoryId = null)
    {

        try {
            $userCategory = $this->getUserCategory($userCategoryId, $id, request()->user()['id']);

            if (collect($userCategory)->isEmpty()) {
                return formatResponse(404, 'user category not found', false);
            }

            $isDeleted = $userCategory->delete();

            if ($isDeleted) {
                return formatResponse(200, 'user category deleted', true);
            }
        } catch (Exception $e) {
            return formatResponse(fetchErrorCode($e), get_class($e) . ": ". $e->getMessage());
        }
    }

    /**
     * Gets a single subCategory
     *
     * @param int $subCategory
     *
     * @return ModelInstance
     */
    public function getUserCategory($userCategoryId, $categoryId, $userId)
    {
        return UserCategory::where(['id' => $userCategoryId, 'category_id' => $categoryId, 'user_id' => $userId])->first();
    }

}

