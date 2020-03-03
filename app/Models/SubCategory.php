<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SubCategory extends BaseModel
{

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'sub_categories';


     /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'sub_title',
        'category_id'
    ];


    /**
     * Get the category for a subcategory.
     */
    public function category()
    {
        return $this->belongsTo(Category::class);
    }
}
