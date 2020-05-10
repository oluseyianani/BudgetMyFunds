<?php

namespace App\Models;

class BudgetTracking extends BaseModel
{
    protected $table = 'budget_expense_trackings';


    /**
     * The name of the "created at" column.
     *
     * @var string
     */
    const CREATED_AT = 'expenses_created_on';

    /**
     * The name of the "updated at" column.
     *
     * @var string
     */
    const UPDATED_AT = 'expenses_updated_on';


    protected $guarded = [];

    /**
    * Relationship with budget
    */
    public function budget()
    {
        return $this->belongsTo(Budget::class, 'id', 'budget_id');
    }
}
