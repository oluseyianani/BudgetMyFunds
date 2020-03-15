<?php

namespace App\Repositories\V1;

use Exception;
use App\Models\Role;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class RoleRepository extends BaseRepository
{

    /**
     * Gets all the subcategories
     *
     * @param $begin
     * @param $perPage
     * @param $sortBy
     * @param $sortDirection
     *
     * @return json
     */
    public function fetchMany(int $begin, int $perPage, string $sortBy, string $sortDirection)
    {
        try {
            $roles = Role::orderBy($sortBy, $sortDirection)
                ->offset($begin)
                ->limit($perPage)
                ->get();

            return formatResponse(200, 'Ok', true, $roles);
        } catch (Exception $e) {
            return formatResponse(fetchErrorCode($e), get_class($e) . ": " . $e->getMessage());
        }
    }


    /**
     * Creates subcategory record
     *
     * @param array $data
     * @param int   $id
     *
     * @return json
     */
    public function create(array $data)
    {
        try {

            $role = Role::create($data);

            return formatResponse(201, 'Role created', true, $role);
        } catch (Exception $e) {
            return formatResponse(fetchErrorCode($e), get_class($e) . ": ". $e->getMessage());
        }
    }

    /**
     * Fetches a subcategory
     *
     * @param int $id
     *
     * @return json
     */
    public function fetchOne(int $id)
    {
        try {
            $role = Role::findOrFail($id);

            return formatResponse(200, 'Ok', true, $role);
        } catch (Exception $e) {
            if ($e instanceof ModelNotFoundException) {
                return formatResponse(404, 'Role not found');
            }
            return formatResponse(fetchErrorCode($e), get_class($e) . ": ". $e->getMessage());
        }
    }

    /**
     * Updates a subcategory resource
     *
     * @param array $data to update
     * @param int   $id   of the resource
     */
    public function update(array $data, int $id)
    {
        try {

            $role =  Role::findOrFail($id)->update($data);

            return formatResponse(200, 'Updated', true, collect(Role::find($id)));
        } catch (Exception $e) {
            if ($e instanceof ModelNotFoundException) {
                return formatResponse(404, 'Role not found');
            }
            return formatResponse(fetchErrorCode($e), get_class($e) . ": ". $e->getMessage());
        }
    }

    /**
     * Destroys a subcategory resource
     *
     * @param int $id of the category resource to destroy
     */
    public function delete(int $id, int $subCategoryId = null)
    {
       //
    }

}

