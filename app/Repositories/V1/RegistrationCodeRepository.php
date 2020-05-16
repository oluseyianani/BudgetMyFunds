<?php

namespace App\Repositories\V1;

use App\Models\RegistrationCode;
use App\Repositories\V1\Interfaces\RegistrationCodeInterface;


class RegistrationCodeRepository implements RegistrationCodeInterface
{
    public function markRegistrationCodeExpired(RegistrationCode $registrationCode)
    {
        $registrationCode->isExpired = true;
        $registrationCode->update();
    }
}
