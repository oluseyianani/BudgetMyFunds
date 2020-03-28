<?php

namespace App\Repositories\V1;

use Exception;
use App\Models\User;
use App\Models\UserProfile;
use App\Models\UserCategory;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class BudgetUserProfileRepository extends BaseRepository
{
    /**
     * Gets all the user profiles
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
        // might implement this for administrative purposes someday
    }


    /**
     * Creates user profile record
     *
     * @param array $data
     * @param int $id
     *
     * @return json
     */
    public function create(array $data, int $userId = null)
    {
        return $this->createOrUpdateUserProfile($data, $userId);
    }

    /**
     * Fetches a user profile
     *
     * @param int $id  - userId
     *
     * @return json
     */
    public function fetchOne(int $id)
    {
        try {

            $userProfile = User::findOrFail($id)->userProfile()->get();

            return formatResponse(200, 'Ok', true, $userProfile);
        } catch (Exception $e) {
            if ($e instanceof ModelNotFoundException) {
                return formatResponse(404, 'User not found');
            }
            return formatResponse(fetchErrorCode($e), get_class($e) . ": ". $e->getMessage());
        }
    }

    /**
     * Updates a user profile resource
     *
     * @param array $data to update
     * @param int $id of the resource
     */
    public function update(array $data, int $id)
    {
       // might implement this for administrative purposes someday
    }

    /**
     * Destroys a user profile resource
     *
     * @param int $id of the user profile resource to destroy
     */
    public function delete(int $id)
    {
        // might implement this for administrative purposes someday
    }

    /**
     * Creates or Updates a user profile resource
     *
     * @param array $data of the user profile
     * @param int $userId of the user profile
     */
    public function createOrUpdateUserProfile(array $data, int $userId)
    {
        try {

            $userProfile = array();

            if ($userId && count($data) > 0) {
                $userProfile['user_id'] = $userId;
                foreach($data as $key => $value) {
                    $userProfile[$key] = $value;
                }
            $userData = User::findOrFail($userId)->userProfile();
            if ($userData->first()) {
                    return formatResponse(200, 'Profile updated', true, collect(UserProfile::find($userData->update($userProfile))));
            } else {
                return formatResponse(201, 'Profile created', true, collect($userData->create($userProfile)));
            }
        }

            return formatResponse(200, 'No data to save for user profile', true, []);

        } catch (Exception $e) {
            if ($e instanceof ModelNotFoundException) {
                return formatResponse(404, 'User not found');
            }
            return formatResponse(fetchErrorCode($e), get_class($e) . ": ". $e->getMessage());
        }

    }

}
