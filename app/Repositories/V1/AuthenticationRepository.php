<?php

namespace App\Repositories\V1;

use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use App\Repositories\V1\Interfaces\AuthenticationInterface;


/**
 * Authentication Repository
 */     
class AuthenticationRepository implements AuthenticationInterface
{
    /**
     * User Registration Logic
     *
     * @param array $credentials
     * @return Json
     */
    public function registerUser(array $credentials)
    {
        $user = User::create($credentials);

        $user->roles()->attach(1, ['approved' => 1]);
        $user['token'] = $user->createToken('BMF')->accessToken;

        return formatResponse(201, 'successfully registered', true, collect($user->only('token', 'email_verified_at', 'roles')));
    }

    /**
     * User Login Logic
     *
     * @param array $credentials
     * @return Json
     */
    public function loginUser(array $credentials)
    {
        if (!Auth::attempt($credentials)) {

            return formatResponse(401, 'Unautorized', false);
        }

        $user = Auth::user();
        $user['token'] = $user->createToken('BMF')->accessToken;

        return formatResponse(200, 'successfully logged in', true, collect($user->only('token', 'email_verified_at', 'roles')));
    }

    /**
     * User Current Session Logout
     *
     * @return Json
     */
    public function logoutUser()
    {
        $user = Auth::user()->token();
        $user->revoke();
        return formatResponse(200, 'successfully logged out', true);
    }

    /**
     * User Logout all Devices
     *
     * @return Json
     */
    public function logoutAllDevice()
    {
        DB::table('oauth_access_tokens')
        ->where('user_id', Auth::user()->id)
        ->update([
            'revoked' => true
        ]);

        return formatResponse(200, 'successfully logged out All Device', true);
    }
}