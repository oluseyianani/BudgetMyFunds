<?php

namespace App\Repositories\V1;

use Exception;
use App\Models\Category;
use App\Repositories\V1\Interfaces\BudgetCategoryInterface;

class BudgetCategoryRepository extends BaseRepository implements BudgetCategoryInterface
{

     /**
     * Gets all the categories
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

            $categories = Category::orderBy($sortBy, $sortDirection)
                                ->with(["subcategory:category_id,id,sub_title"])
                                ->offset($begin)
                                ->limit($perPage)
                                ->get();

            return formatResponse(200, 'Ok', true, $categories);
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

            $data = Category::create($data);

            return formatResponse(201, 'Category created', true, $data);

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
    public function fetchOne(int $id)
    {
        try {
            $category = $this->getACategoryWith($id, ["subcategory"]);

            if ($category->isEmpty()) {
                return formatResponse(404, 'Category not found', false);
            }

            return formatResponse(200, 'Ok', true, $category);

        } catch (Exception $e) {
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

            $category = $this->getCategoryById($id);

            if (collect($category)->isEmpty()) {
                return formatResponse(404, 'Category not found', false);
            }

            $category['title'] = $data['title'];

            if ($category->isDirty()) {
                $category->save();

                return formatResponse(200, 'Category updated', true, collect($category));
            }

            return formatResponse(200, 'No changes made. No update required.', true, collect($category));

        } catch (Exception $e) {
            return formatResponse(fetchErrorCode($e), get_class($e) . ": ". $e->getMessage());
        }
    }

    /**
     * Destroys a category resource
     *
     * @param int $id of the category resource to destroy
     */
    public function delete(int $id)
    {
        $category = $this->getCategoryById($id);

        try {

            if (collect($category)->isEmpty()) {
                return formatResponse(404, 'Category not found', false);
            }

            $isDeleted = $category->delete();

            if ($isDeleted) {
                return formatResponse(200, 'Category deleted', true);
            }
        } catch (Exception $e) {
            return formatResponse(fetchErrorCode($e), get_class($e) . ": ". $e->getMessage());
        }
    }

    /**
     * Finds categories by id
     *
     * @param int $id of the category to find
     * @return ModelInstance
     */
    public function getCategoryById(int $id)
    {
        return Category::where('id',$id)->first();
    }

    /**
     * Eagerloads a category with its relationships
     *
     * @param int $id of category
     * @param array $relationship with categories
     *
     * @return Collection
     */
    public function getACategoryWith(int $id, array $relationships)
    {
        return category::where('id',$id)->with($relationships)->get();
    }
}

