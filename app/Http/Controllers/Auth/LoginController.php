<?php

namespace App\Http\Controllers\Auth;

use Exception;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Providers\RouteServiceProvider;
use App\Http\Requests\CreateLoginRequest;
use Illuminate\Foundation\Auth\AuthenticatesUsers;

class LoginController extends Controller
{


    use AuthenticatesUsers;
    /*
    |--------------------------------------------------------------------------
    | Login Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles authenticating users for the application and
    | redirecting them to your home screen. The controller uses a trait
    | to conveniently provide its functionality to your applications.
    |
    */

    /**
     * Authenticates the user for access to the platform
     *
     * @param App\Http\Requests\CreateLoginRequest
     */
    public function login(CreateLoginRequest $request)
    {

        try {
            if ($this->attemptLogin($request)) {
                $user = $this->guard()->user();
                $user->generateToken();

                return formatResponse(200, 'Successfully logged in', true, $user);
            }

            return $this->sendApiFailedLoginResponse($request);
        } catch (Exception $e) {
            return formatResponse(fetchErrorCode($e), get_class($e) . ": " . $e->getMessage());
        }
    }

    /**
     * Sends an API response on failed login attempt
     *
     * @param App\Http\Requests\Request $request
     * @return json
     */
    public function sendApiFailedLoginResponse(Request $request)
    {
        return formatResponse(400, 'Email or Password Invalid', false);
    }
}
