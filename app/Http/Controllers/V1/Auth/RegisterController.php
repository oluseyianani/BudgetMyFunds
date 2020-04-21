<?php
namespace App\Http\Controllers\V1\Auth;

use Exception;
use App\Models\User;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;
use Illuminate\Auth\Events\Registered;
use App\Providers\RouteServiceProvider;
use App\Http\Requests\CreateUserRequest;
use Illuminate\Support\Facades\Validator;
use Illuminate\Foundation\Auth\RegistersUsers;

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
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Create a new user instance after a valid registration.
     *
     * @param  array $data
     * @return \App\Models\User
     */
    protected function create(array $data)
    {
        try {
            return User::create($data);
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
     *          name="phone_number",
     *          description="The user's phone number",
     *          required=false,
     *          type="string",
     *          in="body",
     *          @SWG\Schema(
     *              @SWG\Property(property="phone_number", type="string", example="+2348011111111111")
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
     *
     *   @SWG\Response(response=422, description="Invalid email or passowrd"),
     *   @SWG\Response(response=201, description="Registered"),
     * )
     *
     * Registers a new user
     * @param App\Http\Request\CreateUserRequest $request
     * @return json
     *
     */
    public function register(CreateUserRequest $request)
    {
        $validated = $request->validated();
        $validated['password'] = bcrypt($request['password']);
        event(new Registered($user = $this->create($validated)));

        return $this->registered($user)
                ?: formatResponse(400, 'Unable to register user', false);
    }

    /**
     * Generates token for the registered user
     *
     * @param App\Models\User $user
     */
    protected function registered(User $user)
    {
        $user->generateToken();

        return formatResponse(201, 'Registered', true, $user->toArray());
    }
}
