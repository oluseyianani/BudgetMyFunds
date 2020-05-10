<?php

namespace App\Models;

use App\Models\User;
use App\Models\Category;
use App\Models\BaseModel;
use App\Models\SubCategory;
use Illuminate\Database\Eloquent\SoftDeletes;

class Budget extends BaseModel
{
    use SoftDeletes;

    /**
     * The name of the "created at" column.
     *
     * @var string
     */
    const CREATED_AT = 'budget_created_on';

    /**
     * The name of the "updated at" column.
     *
     * @var string
     */
    const UPDATED_AT = 'budget_updated_on';

    /**
     * The name of the "deleted at" column.
     *
     * @var string
     */
    const DELETED_AT = 'budget_deleted_on';


    protected $guarded = [];


    protected static function boot()
    {
        parent::boot();

        static::deleting(function ($budget) {
            $budget->budgetTracking()->delete();
        });
    }

    /**
     * Relationship with user
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Relationship with Category
     */
    public function category()
    {
        return $this->hasOne(Category::class, 'id', 'category_id');
    }

    /**
    * Relationship with SubCategory
    */
    public function subCategory()
    {
        return $this->hasOne(SubCategory::class, 'id', 'sub_category_id');
    }

    /**
    * Scope to filter budget meant for the logged in user
    */
    public function scopeForLoggedInUser($query, $userId = null)
    {
        $user = $userId ?: auth()->user()['id'];
        return $query->where('user_id', $user);
    }

    public function budgetTracking()
    {
        return $this->hasMany(BudgetTracking::class, 'budget_id');
    }
}
