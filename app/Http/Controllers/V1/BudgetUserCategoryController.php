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
