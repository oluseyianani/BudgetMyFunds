<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use App\Models\RegistrationCode;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use App\Repositories\V1\RegistrationCodeRepository;

class TrackAndUpdateExpiryTime implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $registeredCode;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(RegistrationCode $registrationCode)
    {
        $this->registeredCode = $registrationCode;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle(RegistrationCodeRepository $registeredPhone)
    {
        $registeredPhone->markRegistrationCodeExpired($this->registeredCode);
    }
}
