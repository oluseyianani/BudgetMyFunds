<?php

namespace App\Repositories\V1\Interfaces;

use App\Models\RegistrationCode;

interface RegistrationCodeInterface
{
    /**
     * Gets a single subCategory
     *
     * @param modelInstance RegistrationCode
     */
    public function markRegistrationCodeExpired(RegistrationCode $registrationCode);
}
