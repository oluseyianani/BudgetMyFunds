<?php

namespace App\Repositories\V1\Interfaces;

/**
 * Authentication Interface
 */
interface AuthenticationInterface
{
    /**
     * User Registration Interface
     *
     * @param array $credentials
     * @return Json
     */
    public function registerUser(array $credentials);

    /**
     * User Login Interface
     *
     * @param array $credentials
     * @return Json
     */
    public function loginUser(array $credentials);

    /**
     * User Logout Current Session Interface
     *
     * @return Json
     */
    public function logoutUser();

    /**
     * User Logout All Device Interface
     *
     * @return Json
     */
    public function logoutAllDevice();
}