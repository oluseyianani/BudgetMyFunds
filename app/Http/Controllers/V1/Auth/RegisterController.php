<?php
namespace App\Http\Controllers\V1\Auth;

use Exception;
use App\Models\User;
use App\Models\RegistrationCode;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;
use Illuminate\Auth\Events\Registered;
use App\Events\RegistrationCodeCreated;
use App\Providers\RouteServiceProvider;
use Illuminate\Support\Facades\Artisan;
use App\Http\Requests\CreateUserRequest;
use Illuminate\Support\Facades\Validator;
use Illuminate\Foundation\Auth\RegistersUsers;
use App\Http\Requests\CreateValidationCodeRequest;
use App\Http\Requests\CreateRegistrationCodeRequest;

class RegisterController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Register Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles the registration of new users as well as their
    | validation and creation. By default this controller uses a trait to
    | provide this functionality without requiring any additional code.
    |
    */


    /**
     * Create a new user instance after a valid registration.
     *
     * @param  array $data
     * @return \App\Models\User
     */
    protected function create(array $data)
    {
        try {
            $user = User::create([
                'email' => $data['email'],
                'password' => $data['password'],
                'phone' => $data['phone']
            ]);

            Artisan::call('registrationCode:update', ['data' => $data]);

            return $user;
        } catch (Exception $e) {
            return formatResponse(fetchErrorCode($e), get_class($e) . ": " . $e->getMessage());
        }
    }

    /**
     * @SWG\Post(
     *   path="/auth/register",
     *   description="Registers a user",
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
     *          name="phone",
     *          description="The user's phone number",
     *          required=false,
     *          type="string",
     *          in="body",
     *          @SWG\Schema(
     *              @SWG\Property(property="phone", type="string", example="+2348011111111111")
     *         )
     *      ),
     *      @SWG\Parameter(
     *          name="password",
     *          description="Users password. Minimum eight characters, at least one letter and one number",
     *          required=true,
     *          type="integer",
     *          in="body",
     *          @SWG\Schema(
     *              @SWG\Property(property="password", type="string", example="password123")
     *         )
     *      ),
     *      @SWG\Parameter(
     *          name="password_confirmation",
     *          description="Users confirm password. Minimum eight characters, at least one letter and one number",
     *          required=true,
     *          type="string",
     *          in="body",
     *          @SWG\Schema(
     *              @SWG\Property(property="password_confirmation", type="string", example="password123")
     *         )
     *      ),
     *      @SWG\Parameter(
     *          name="isVerified",
     *          description="If registration code is verified, this should be true",
     *          required=true,
     *          type="boolean",
     *          in="body",
     *          @SWG\Schema(
     *              @SWG\Property(property="isVerified", type="boolean", example="true")
     *         )
     *      ),
     *       @SWG\Parameter(
     *          name="code",
     *          description="Validated registration code",
     *          required=true,
     *          type="string",
     *          in="body",
     *          @SWG\Schema(
     *              @SWG\Property(property="code", type="boolean", example="true")
     *         )
     *      ),
     *
     *   @SWG\Response(response=422, description="Invalid email or passowrd"),
     *   @SWG\Response(response=201, description="Registered"),
     * )
     *
     * Registers a new user
     * @param App\Http\Request\CreateUserRequest $request
     *
     * @return json
     *
     */
    public function register(CreateUserRequest $request)
    {
        $validated = $request->validated();

        if ($validated['isVerified']) {
            $validated['password'] = bcrypt($request['password']);

            event(new Registered($user = $this->create($validated)));
            return $this->registered($user)
                    ?: formatResponse(400, 'Unable to register user', false);
        }

        return formatResponse(400, 'You need to verify your phone');
    }

    /**
     * Generates token for the registered user
     *
     * @param App\Models\User $user
     */
    protected function registered(User $user)
    {
        $user->generateToken();

        $user->roles()->attach(1, ['approved' => 1]);

        return formatResponse(201, 'Registered', true, $user->toArray());
    }

    /**
     * Gets the registration code for user's phone
     *
     * @param CreateRegistrationCodeRequest $request
     *
     * @return json
     */
    public function getCode(CreateRegistrationCodeRequest $request)
    {
        $phone = $request->validated();

        $isVerified = RegistrationCode::where(['phone' => $phone['phone'], 'isVerified' => true])->first();

        if ($isVerified) {
            return formatResponse(401, 'Unable to verify this number. Consider changing the number or contact the administrator', false);
        }

        $code = random_int(100000, 999999);

        $data = RegistrationCode::create([
            'phone' => $phone['phone'],
            'code' => $code
        ]);

        event(new RegistrationCodeCreated($data));
        return formatResponse(200, 'Registration code sent', true);
    }

    /**
     * Validates the registration code for a user
     * @param CreateValidationCodeRequest $request
     *
     * @return json
     */
    public function validateCode(CreateValidationCodeRequest $request)
    {
        $data = $request->validated();
        $registrationUser = RegistrationCode::where([
            'phone' => $data['phone'],
            'code' => $data['code']
            ])->latest()->get();

        if ($registrationUser->isEmpty()) {
            return formatResponse(404, 'Invalid registration code');
        }
        if ($registrationUser[0]['isExpired']) {
            return formatResponse(401, 'Registration code is expired. Please request a new one');
        }

        return formatResponse(200, 'Ok', true, $registrationUser);
    }
}
