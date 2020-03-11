<?php

namespace App\Repositories\V1\Interfaces;

interface BudgetSubCategoryInterface
{
    /**
     * Gets a single subCategory
     *
     * @param integer $subCategory
     */
    public function getSubCategory(int $subCategoryId);
}
