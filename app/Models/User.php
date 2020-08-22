<?php

namespace App\Models;

use App\Models\Role;
use App\Models\Category;
use Illuminate\Support\Str;
use Laravel\Passport\HasApiTokens;
use Illuminate\Support\Facades\Hash;
use App\Notifications\VerifyApiEmail;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable implements MustVerifyEmail
{
    use Notifiable, SoftDeletes, HasApiTokens;

    public function __construct(array $attributes = [])
    {
        parent::__construct($attributes);
        $this->connection = env('APP_ENV') === 'testing' ? 'sqlite' : env('DB_CONNECTION');
    }

    public static function getFullTableName()
    {
        $model = new static();
        return "{$model->getConnectionName()}.{$model->getTable()}";
    }

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'email', 'password', 'email_verified_at',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
       'password', 'remember_token', 'deleted_at'
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
    protected $with = ['roles'];


    /**
     * Mutator
     *
     * @param string $password
     * @return void
     */
    public function setPasswordAttribute($password)
    {
        $this->attributes['password'] = Hash::make($password);
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
    public function roles()
    {
        return $this->belongsToMany(Role::class)->withTimestamps()->withPivot(['approved']);
    }

    /**
     * Checks isOwner Role
     *
     * @return boolean
     */
    public function isOwner()
    {
        return in_array('Owner', collect($this->roles)->pluck('role')->toArray());
    }

    /**
     * Checks for isAdmin Role
     *
     * @return boolean
     */
    public function isAdmin()
    {
        return in_array('Admin', collect($this->roles)->pluck('role')->toArray());
    }

    /**
     * Checks for isCollaborator Role
     *
     * @return boolean
     */
    public function isCollaborator()
    {
        return in_array('Collaborator', collect($this->roles)->pluck('role')->toArray());
    }

    /**
     * Checks for isSuperAdmin Role
     *
     * @return boolean
     */
    public function isSuperAdmin()
    {
        return in_array('Super admin', collect($this->roles)->pluck('role')->toArray());
    }

    /**
     * Relations with User Profile
     *
     * @return Builder
     */
    public function userProfile()
    {
        return $this->hasOne(UserProfile::class, 'user_id');
    }

    /**
     * Filters Roles Relations for Approved roles
     *
     * @return Builder
     */
    public function approvedRoles()
    {
        return $this->belongsToMany(Role::class)->wherePivot('approved', 1);
    }
}
