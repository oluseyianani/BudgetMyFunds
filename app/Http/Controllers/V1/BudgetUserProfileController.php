<?php

namespace App\Http\Controllers\V1;

use App\UserProfile;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Requests\CreateUserProfileRequest;
use App\Repositories\V1\BudgetUserProfileRepository;

class BudgetUserProfileController extends Controller
{

    protected $userProfile;
    public function __construct(BudgetUserProfileRepository $BudgetUserProfileRepository)
    {
        $this->userProfile = $BudgetUserProfileRepository;
    }


    /**
     * @SWG\Post(
     *    path="/profile",
     *    description="Creates or Updates a user profile",
     *    consumes={"multipart/form-data", "application/json"},
     *       @SWG\Parameter(
     *          name="Authorization",
     *          description="Bearer token, needed for authentication",
     *          required=true,
     *          type="string",
     *          in="header"
     *        ),
     *      @SWG\Parameter(
     *          name="photo_url",
     *          description="The url of the profile picture",
     *          required=false,
     *          in="body",
     *          @SWG\Schema(
     *              @SWG\Property(property="photo_url", type="string", example="https://picsum.photos/200/300.jpg")
     *         )
     *      ),
     *     @SWG\Parameter(
     *          name="first_name",
     *          description="The first name of the user",
     *          required=false,
     *          in="body",
     *          @SWG\Schema(
     *              @SWG\Property(property="first_name", type="string", example="Oluseyi")
     *         )
     *      ),
     *       @SWG\Parameter(
     *          name="last_name",
     *          description="The last name of the user",
     *          required=false,
     *          in="body",
     *          @SWG\Schema(
     *              @SWG\Property(property="last_name", type="string", example="Oluseyi")
     *         )
     *      ),
     *      @SWG\Parameter(
     *          name="gender",
     *          description="The gender of the user",
     *          required=false,
     *          in="body",
     *          @SWG\Schema(
     *              @SWG\Property(property="gender", type="string", example="M or F")
     *         )
     *      ),
     *      @SWG\Parameter(
     *          name="date_of_birth",
     *          description="The date of birth of the user",
     *          required=false,
     *          in="body",
     *          @SWG\Schema(
     *              @SWG\Property(property="date_of_birth", type="string", example="Y-m-d")
     *         )
     *      ),
     *      @SWG\Parameter(
     *          name="postal_address",
     *          description="The postal address of the user",
     *          required=false,
     *          in="body",
     *          @SWG\Schema(
     *              @SWG\Property(property="postal_address", type="string", example="address example")
     *         )
     *      ),
     *    @SWG\Response(response=401, description="Invalid token"),
     *    @SWG\Response(response=201, description="Profile created"),
     *    @SWG\Response(response=200, description="Profile updated")
     * )
     *
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(CreateUserProfileRequest $request)
    {
        $this->authorize('ownerAccess', $user = $request->user());
        return $this->userProfile->create($request->validated(), $user['id']);
    }


    /**
     *   @SWG\Get(
     *   path="/profile/{id}",
     *   description="Returns the specified user profile.",
     *      @SWG\Parameter(
     *          name="Authorization",
     *          description="Bearer token, needed for authentication",
     *          required=true,
     *          type="string",
     *          in="header"
     *      ),
     *      @SWG\Parameter(
     *          name="id",
     *          description="The id of the user to be returned.",
     *          required=true,
     *          type="integer",
     *          in="path"
     *      ),
     *   @SWG\Response(response=401, description="Invalid token"),
     *   @SWG\Response(response=404, description="user not found"),
     *   @SWG\Response(response=200, description="Ok"),
     * )
     *
     * Display the specified resource.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\User  $userId
     * @return \Illuminate\Http\Response
     */
    public function show(Request $request, int $userId)
    {
        $this->authorize('ownerAccess', $request->user());
        return $this->userProfile->fetchOne($userId);
    }


    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\UserProfile  $userProfile
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, UserProfile $userProfile)
    {
        //
    }

}
