<?php

namespace App\Repositories\V1;

abstract class BaseRepository
{
    abstract function fetchMany(int $begin, int $perPage, string $sortBy, string $sortDirection);

    abstract function create(array $data);

    abstract function fetchOne(int $id);

    abstract function update(array $data, int $id);

    abstract function delete(int $id);
}
