<?php

namespace App\Models;

use Twilio\Rest\Client;
use App\Jobs\TrackAndUpdateExpiryTime;
use Illuminate\Database\Eloquent\Model;

class RegistrationCode extends Model
{

    /**
     * Twilio Account SID
     */
    protected $account_sid;

    /**
     * Twilio Auth Token
     */
    protected $auth_token;

    /**
     * Twilio from
     */
    protected $twilio_from;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['phone', 'code', 'isVerified', 'isExpired'];

    protected static function boot()
    {
        parent::boot();

        static::created(function ($registrationCode) {
            TrackAndUpdateExpiryTime::dispatch($registrationCode)->delay(now()->addMinutes(1));
        });
    }

    /**
     * Sends registration code to user's phone
     *
     * @param string $phone
     * @param string $code
     */
    public function sendCode($phone, $code)
    {
        $this->account_sid = config('services.twilio.account_sid');
        $this->auth_token = config('services.twilio.auth_token');
        $this->twilio_from = config('services.twilio.from');

        $client = new Client($this->account_sid, $this->auth_token);

        $client->messages->create(
            $phone,
            array(
                'from' => $this->twilio_from,
                'body' => "Here is your BudgetMyFunds registration code {$code}."
            )
        );
    }
}
