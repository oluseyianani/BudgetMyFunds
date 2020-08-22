<?php

namespace App\Http\Controllers\V1\Auth;


use App\Http\Controllers\Controller;
use App\Http\Requests\CreateLoginRequest;
use App\Http\Requests\CreateLogoutRequest;
use App\Http\Requests\CreateRegistrationRequest;
use App\Repositories\V1\Interfaces\AuthenticationInterface;

class AuthenticationController extends Controller
{

    public $authenticate;

    public function __construct(AuthenticationInterface $authenticateInterface)
    {
        $this->authenticate = $authenticateInterface;
    }

    public function register(CreateRegistrationRequest $request)
    {
        return $this->authenticate->registerUser($request->validated());
    }

    public function login(CreateLoginRequest $request)
    {
        return $this->authenticate->loginUser($request->validated());
    }

    public function logout()
    {
        return $this->authenticate->logoutUser();
    }

    public function logoutAllDevice()
    {
        return $this->authenticate->logoutAllDevice();
    }
}
