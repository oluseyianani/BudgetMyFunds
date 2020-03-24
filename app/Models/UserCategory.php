<?php

namespace App\Models;

use App\Models\User;
use Illuminate\Database\Eloquent\Model;

class UserCategory extends BaseModel
{

     /**
     * The table associated with the model.
     *
     * @var string
    */
    protected $table = 'user_categories';


     /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'category_id',
        'user_id',
        'title'
    ];
}
