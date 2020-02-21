<?php

namespace App\Repositories\V1\Interfaces;

interface BudgetCategoryInterface
{
    /**
     * Finds categories by id
     *
     * @param int $id of the category to find
     */
    public function getCategoryById(int $id);

    
    /**
     * Eagerloads a category with its relationships
     *
     * @param int $id of category
     * @param array $relationship with categories
     */
    public function getACategoryWith(int $id, array $relationship);
}
