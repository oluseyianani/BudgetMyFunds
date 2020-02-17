<?php

namespace App\Repositories\V1;

abstract class BaseRepository
{
    abstract function fetchMany($begin, $perPage, $sortBy, $sortDirection);

    abstract function create($data);

    abstract function fetchOne($id);

    abstract function update($data, $id);

    abstract function delete($id);
}
