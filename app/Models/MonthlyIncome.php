<?php

namespace App\Models;

use Illuminate\Database\Eloquent\SoftDeletes;

class MonthlyIncome extends BaseModel
{
    use SoftDeletes;

    /**
     * Table name for model
     */
    protected $table = 'monthly_income_tracker';

    /**
     * Guarded columns for mass assignment
     */
    protected $guarded = [];


    /**
     * Scope for authorized user
     */
    public function scopeForAuthorizedUser($query, $creatorId)
    {
        return $query->where('creator', $creatorId);
    }
}
