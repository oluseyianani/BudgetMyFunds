<?php

namespace App\Models;

use Illuminate\Database\Eloquent\SoftDeletes;

class GoalTracking extends BaseModel
{
    use SoftDeletes;

    /**
    * The name of the "created at" column.
    *
    * @var string
    */
    const CREATED_AT = 'date_contributed';

    /**
     * The name of the "updated at" column.
     *
     * @var string
     */
    const UPDATED_AT = 'date_updated';

    /**
     * The name of the "deleted at" column.
     *
     * @var string
     */
    const DELETED_AT = 'date_deleted';

    /**
     * Relationship with goal
     */
    public function goal()
    {
        return $this->belongsTo(Goal::class);
    }
}
