<?php

namespace App\Http\Controllers\V1\Auth;

use Exception;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Http\Requests\CreateLoginRequest;
use App\Http\Requests\CreateMobileLoginRequest;
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
     *
     * @SWG\Post(
     *   path="/auth/login",
     *   description="login a user",
     *   consumes={"multipart/form-data", "application/json"},
     *       @SWG\Parameter(
     *          name="email",
     *          description="user's email",
     *          required=true,
     *          type="string",
     *          in="body",
     *          @SWG\Schema(
     *              @SWG\Property(property="email", type="string", example="john.doe@email.com")
     *         )
     *      ),
     *      @SWG\Parameter(
     *          name="password",
     *          description="Users password",
     *          required=true,
     *          type="integer",
     *          in="body",
     *          @SWG\Schema(
     *              @SWG\Property(property="password", type="string", example="password123")
     *         )
     *      ),
     *   @SWG\Response(response=422, description="Invalid email or passowrd"),
     *   @SWG\Response(response=201, description="Successfully logged in"),
     * )
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

            return $this->sendApiFailedLoginResponse();
        } catch (Exception $e) {
            return formatResponse(fetchErrorCode($e), get_class($e) . ": " . $e->getMessage());
        }
    }

    /**
     * Sends an API response on failed login attempt
     *
     * @return json
     */
    public function sendApiFailedLoginResponse()
    {
        return formatResponse(400, 'Email or Password Invalid', false);
    }

    public function mobileLogin(CreateMobileLoginRequest $request)
    {
        if ($this->attemptMobileLogin($request)) {
            return $this->sendLoginMobileResponse();
        }

        return $this->sendFailedLoginMobileResponse();
    }

    protected function attemptMobileLogin(Request $request)
    {
        return $this->guard()->attempt(
            $this->mobileCredentials($request)
        );
    }

    protected function mobileCredentials(Request $request)
    {
        return $request->only($this->phoneNumber(), 'password');
    }

    protected function phoneNumber()
    {
        return 'phone';
    }

    protected function sendLoginMobileResponse()
    {
        $user = $this->guard()->user();
        $user->generateToken();

        return formatResponse(200, 'Successfully logged in', true, $user);
    }

    protected function sendFailedLoginMobileResponse()
    {
        return formatResponse(400, 'Phone or Password Invalid', false);
    }
}
