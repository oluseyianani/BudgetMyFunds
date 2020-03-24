<?php

namespace App\Http\Controllers\V1;

use App\Models\User;
use App\Models\UserCategory;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Requests\CreateUserCategoryRequest;
use App\Http\Requests\UpdateUserCategoryRequest;
use App\Repositories\V1\BudgetUserCategoryRepository;

class BudgetUserCategoryController extends Controller
{

    protected $userCategory;
    public function __construct(BudgetUserCategoryRepository $userCategoryRepository)
    {
        $this->userCategory = $userCategoryRepository;
    }


    /**
     * @SWG\Post(
     *    path="/category/{id}/usercategory",
     *    description="Creates a new user category",
     *    consumes={"multipart/form-data", "application/json"},
     *       @SWG\Parameter(
     *          name="Authorization",
     *          description="Bearer token, needed for authentication",
     *          required=true,
     *          type="string",
     *          in="header"
     *        ),
     *       @SWG\Parameter(
     *          name="id",
     *          description="The id of the category to be returned.",
     *          required=true,
     *          type="integer",
     *          in="path"
     *      ),
     *      @SWG\Parameter(
     *          name="title",
     *          description="The title of the userCategory",
     *          required=true,
     *          in="body",
     *          @SWG\Schema(
     *              @SWG\Property(property="title", type="string", example="savings for me")
     *         )
     *      ),
     *    @SWG\Response(response=401, description="Invalid token"),
     *    @SWG\Response(response=404, description="Category not found"),
     *    @SWG\Response(response=201, description="user Category Created"),
     * )
     *
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(CreateUserCategoryRequest $request, int $id)
    {
        $this->authorize('userCategory', $user = request()->user());
        return $this->userCategory->create($request->validated(), $id, $user['id']);
    }


    /**
     * @SWG\Put(
     *    path="/category/{id}/usercategory/{userCategoryId}",
     *    description="Edits a user category",
     *    consumes={"multipart/form-data", "application/json"},
     *       @SWG\Parameter(
     *          name="Authorization",
     *          description="Bearer token, needed for authentication",
     *          required=true,
     *          type="string",
     *          in="header"
     *        ),
     *       @SWG\Parameter(
     *          name="id",
     *          description="The id of the category to be returned.",
     *          required=true,
     *          type="integer",
     *          in="path"
     *      ),
     *       @SWG\Parameter(
     *          name="usercategoryId",
     *          description="The id of the user category to be updated.",
     *          required=true,
     *          type="integer",
     *          in="path"
     *      ),
     *      @SWG\Parameter(
     *          name="title",
     *          description="The title of the userCategory",
     *          required=true,
     *          in="body",
     *          @SWG\Schema(
     *              @SWG\Property(property="title", type="string", example="My savings updated")
     *         )
     *      ),
     *    @SWG\Response(response=401, description="Invalid token"),
     *    @SWG\Response(response=404, description="Category not found"),
     *    @SWG\Response(response=200, description="User Category Updated"),
     * )
     *
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\UserCategory  $userCategory
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateUserCategoryRequest $request, int $id, UserCategory $userCategoryId)
    {
        $this->authorize('updateUserCategory', $userCategoryId);
        return $this->userCategory->update($request->validated(), $id, $userCategoryId['id']);
    }

    /**
     * @SWG\Delete(
     *    path="/category/{id}/usercategory/{userCategoryId}",
     *    description="Edits a user category",
     *    consumes={"multipart/form-data", "application/json"},
     *       @SWG\Parameter(
     *          name="Authorization",
     *          description="Bearer token, needed for authentication",
     *          required=true,
     *          type="string",
     *          in="header"
     *        ),
     *       @SWG\Parameter(
     *          name="id",
     *          description="The id of the category to be returned.",
     *          required=true,
     *          type="integer",
     *          in="path"
     *      ),
     *       @SWG\Parameter(
     *          name="userCategoryId",
     *          description="The id of the user-category to be deleted.",
     *          required=true,
     *          type="integer",
     *          in="path"
     *      ),
     *    @SWG\Response(response=401, description="Invalid token"),
     *    @SWG\Response(response=404, description="Category not found"),
     *    @SWG\Response(response=200, description="User Category deleted"),
     * )
     *
     * Remove the specified resource from storage.
     *
     * @param  \App\UserCategory  $userCategory
     * @return \Illuminate\Http\Response
     */
    public function destroy(int $id, UserCategory $userCategoryId)
    {
        $this->authorize('deleteUserCategory', $userCategoryId);
        return $this->userCategory->delete($id, $userCategoryId['id']);
    }
}
