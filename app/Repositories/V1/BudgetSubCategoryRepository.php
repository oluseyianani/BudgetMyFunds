<?php

namespace App\Repositories\V1;

use Exception;
use App\Models\Category;
use App\Models\SubCategory;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use App\Repositories\V1\Interfaces\BudgetSubCategoryInterface;

class BudgetSubCategoryRepository extends BaseRepository implements BudgetSubCategoryInterface
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

           $subCategory = Category::withSubCategory($categoryId)->create($data);

           if ($subCategory) {
               return formatResponse(201, 'Created', true, collect($subCategory));
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
     */
    public function update(array $data, int $id, int $subCategoryId= null)
    {
        try {

            $subCategory = Category::withSubCategory($id)->findOrFail($subCategoryId)->update($data);

            if ($subCategory) {
                return formatResponse(201, 'Updated', true, collect($this->getSubCategory($subCategory)));
            }

        } catch (Exception $e) {
            if ($e instanceof ModelNotFoundException) {
                return formatResponse(404, 'Category or SubCategory not found');
            }
            return formatResponse(fetchErrorCode($e), get_class($e) . ": " . $e->getMessage());
        }
    }

    /**
     * Destroys a subcategory resource
     *
     * @param int $id of the category resource to destroy
     */
    public function delete(int $id, int $subCategoryId = null)
    {
        try {

            $isDeleted = Category::withSubCategory($id)->findOrFail($subCategoryId)->delete();

            if ($isDeleted) {
                return formatResponse(200, 'Category deleted', true);
            }

        } catch (Exception $e) {
            if ($e instanceof ModelNotFoundException) {
                return formatResponse(404, 'Category or SubCategory not found');
            }
            return formatResponse(fetchErrorCode($e), get_class($e) . ": " . $e->getMessage());
        }
    }

    /**
     * Gets a single subCategory
     *
     * @param int $subCategory
     *
     * @return ModelInstance
     */
    public function getSubCategory($subCategoryId)
    {
        return SubCategory::where('id', $subCategoryId)->first();
    }

}

