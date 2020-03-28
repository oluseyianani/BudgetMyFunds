<?php

namespace App\Models;

use App\Models\Role;
use App\Models\Category;
use Illuminate\Support\Str;
use App\Notifications\VerifyApiEmail;
use Illuminate\Notifications\Notifiable;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable implements MustVerifyEmail
{
    use Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'email', 'phone_number', 'password', 'email_verified_at',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    /**
     * Eagerloads role on User request
     */
    protected $with = ['role'];


    /**
     * generates a new token for user
    */
    public function generateToken()
    {
        $this->api_token = Str::random(60);
        $this->save();

        return $this;
    }

    /**
     * Sends verification email to user
     */
    public function sendApiEmailVerificationNotification()
    {
        $this->notify(new VerifyApiEmail);
    }

    /**
     * Get the category by a user.
     */
    public function category()
    {
        return $this->hasMany(Category::class, 'creator');
    }

    /**
     * Ges user role.
     */
    public function role()
    {
        return $this->hasOne(Role::class, 'id', 'role_id');
    }

    public function isOwner()
    {
        return $this->role->role === 'Owner';
    }

    public function isAdmin()
    {
        return $this->role->role === 'Admin';
    }

    public function isCollaborator()
    {
        return $this->role->role === 'Collaborator';
    }

    public function isSuperAdmin()
    {
        return $this->role->role === 'Super admin';
    }

    public function userProfile()
    {
        return $this->hasOne(UserProfile::class, 'user_id');
    }

}
