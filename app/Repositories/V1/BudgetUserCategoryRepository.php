<?php

namespace App\Repositories\V1;

use Exception;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use App\Repositories\V1\Interfaces\BudgetSubCategoryInterface;

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
       dd();
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
     */
    public function update(array $data, int $id, int $userCategoryId= null)
    {
       //
    }

    /**
     * Destroys a subcategory resource
     *
     * @param int $id of the category resource to destroy
     */
    public function delete(int $id, int $userCategoryId = null)
    {
        //
    }

    /**
     * Gets a single subCategory
     *
     * @param int $subCategory
     *
     * @return ModelInstance
     */
    // public function getSubCategory($subCategoryId)
    // {
    //     return SubCategory::where('id', $subCategoryId)->first();
    // }

}

