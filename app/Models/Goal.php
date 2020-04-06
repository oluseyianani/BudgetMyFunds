<?php

namespace App\Models;

use App\Models\Goal;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Goal extends BaseModel
{
    use SoftDeletes;

    /**
     * The name of the "created at" column.
     *
     * @var string
     */
    const CREATED_AT = 'goal_created_on';

    /**
     * The name of the "updated at" column.
     *
     * @var string
     */
    const UPDATED_AT = 'goal_updated_on';

    /**
     * The name of the "deleted at" column.
     *
     * @var string
     */
    const DELETED_AT = 'goal_deleted_on';

    protected $fillable = [
        'title', 'description', 'amount', 'monthly_contributions', 'due_date', 'auto_compute', 'user_id', 'goal_categories_id'
    ];

    /**
     * Relationship with goal trackings
     */
    public function goalTrackings()
    {
        return $this->hasMany(GoalTracking::class, 'goal_id');
    }

    /**
     * Relationship with user
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function goalCategory()
    {
        return $this->hasOne(GoalCategory::class, 'id', 'goal_categories_id');
    }

    /**
     * Scope where goal is not completed
     */
    public function scopeUnfinishedGoal()
    {
        return $this->where('completed', 0);
    }

    public function scopeForLoggedInUser($query, $userId = null)
    {
        $user = $userId ?: auth()->user()['id'];
        return $query->where('user_id', $user);
    }

    public function totalContributions()
    {
        return $this->hasMany(GoalTracking::class, 'goal_id')->select(['goal_trackings.goal_id', DB::raw('SUM(amount_contributed) as total_contributed, COUNT(*) AS contribution_cycle')])->groupBy('goal_id');
    }
}
