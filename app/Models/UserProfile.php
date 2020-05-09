<?php

namespace App\Models;

class UserProfile extends BaseModel
{

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'photo_url', 'first_name', 'last_name', 'gender', 'date_of_birth', 'postal_address',
    ];

    public function user()
    {
        return $this->hasOne(User::class, 'id', 'user_id');
    }
}
