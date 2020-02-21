<?php

namespace App\Repositories\V1;

use App\Repositories\V1\Interfaces\BudgetCategoryInterface;

class BudgetCategoryRepository extends BaseRepository implements BudgetCategoryInterface
{

    public function fetchMany($begin, $perPage, $sortBy, $sortDirection)
    {
        dd('in here');
    }


    public function create($data)
    {
        //
    }

    public function fetchOne($id)
    {
        //
    }

    public function update($data,$id)
    {
        //
    }

    public function delete($id)
    {
        //
    }
}

