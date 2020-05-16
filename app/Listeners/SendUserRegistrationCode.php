<?php

namespace App\Listeners;

use App\Models\RegistrationCode;
use App\Events\RegistrationCodeCreated;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

class SendUserRegistrationCode implements ShouldQueue
{

    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     *
     * @param  object  $event
     * @return void
     */
    public function handle(RegistrationCodeCreated $event)
    {
        $data = $event->registerData;

        (new RegistrationCode())->sendCode($data['phone'], $data['code']);
    }
}
