<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BaseModel extends Model
{

    public function __construct(array $attributes = [])
    {
        parent::__construct($attributes);
        $this->connection = env('APP_ENV') === 'testing' ? 'sqlite' : 'mysql';
    }

    public static function getFullTableName()
    {
        $model = new static();
        return "{$model->getConnectionName()}.{$model->getTable()}";
    }


}
