<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Category extends BaseModel
{

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'categories';


     /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'title',
        'creator'
    ];


    /**
     * Get the subcategories for a category.
     */
    public function subcategory()
    {
        return $this->hasMany(SubCategory::class, 'category_id');
    }

    /**
     * Gets a category and associated subcategories
     *
     * @param Builder $query
     * @param int $categoryId
     */
    public function scopeWithSubCategory($query, $categoryId)
    {
        return $query->findOrFail($categoryId)->subCategory();
    }
}
