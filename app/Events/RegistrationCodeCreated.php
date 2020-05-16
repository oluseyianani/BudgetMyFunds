<?php

namespace App\Events;

use App\Models\RegistrationCode;
use Illuminate\Queue\SerializesModels;
use Illuminate\Foundation\Events\Dispatchable;

class RegistrationCodeCreated
{
    use Dispatchable, SerializesModels;

    public $registerData;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct(RegistrationCode $registrationCodeData)
    {
        $this->registerData = $registrationCodeData;
    }
}
