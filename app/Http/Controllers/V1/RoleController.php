<?php

namespace App\Http\Controllers\V1;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Repositories\V1\RoleRepository;
use App\Http\Requests\CreateRoleRequest;
use App\Http\Requests\UpdateRoleRequest;

class RoleController extends Controller
{

    protected $roleRepository;
    public function __construct(RoleRepository $roleRepository)
    {
        $this->role = $roleRepository;
    }

    /**
     *   @SWG\Get(
     *   path="/role",
     *   description="Gets all Roles",
     *   consumes={"multipart/form-data", "application/json"},
     *       @SWG\Parameter(
     *          name="Authorization",
     *          description="Bearer token, needed for authentication",
     *          required=true,
     *          type="string",
     *          in="header"
     *      ),
     *      @SWG\Parameter(
     *          name="per_page",
     *          description="The number of role to return in a list or page.",
     *          required=false,
     *          type="integer",
     *          in="query"
     *      ),
     *      @SWG\Parameter(
     *          name="begin",
     *          description="The offset to start fetching results from.",
     *          required=false,
     *          type="integer",
     *          in="query"
     *      ),
     *      @SWG\Parameter(
     *          name="sort_by",
     *          description="The column to sort results with. The default is role.",
     *          required=false,
     *          type="string",
     *          in="query"
     *      ),
     *      @SWG\Parameter(
     *          name="sort_direction",
     *          description="The direction for sorting, either asc (asceding) or desc (descending). Default is ascending",
     *          required=false,
     *          type="string",
     *          in="query"
     *      ),
     *
     *   @SWG\Response(response=401, description="Invalid token"),
     *   @SWG\Response(response=200, description="OK"),
     * )
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $begin = ($request->filled('begin')) ? $request->query('begin') : 0;
        $perPage = ($request->filled('per_page')) ? $request->query('per_page') : 10;
        $sortBy = ($request->filled('sort_by')) ? $request->query('sort_by') : "role";
        $sortDirection = ($request->filled('sort_direction')) ? $request->query('sort_direction') : "asc";

        return $this->role->fetchMany($begin, $perPage, $sortBy, $sortDirection);
    }

    /**
     * @SWG\Post(
     *    path="/role",
     *    description="Creates a new category",
     *    consumes={"multipart/form-data", "application/json"},
     *       @SWG\Parameter(
     *          name="Authorization",
     *          description="Bearer token, needed for authentication",
     *          required=true,
     *          type="string",
     *          in="header"
     *        ),
     *      @SWG\Parameter(
     *          name="role",
     *          description="The role to be created",
     *          required=true,
     *          in="body",
     *          @SWG\Schema(
     *              @SWG\Property(property="role", type="string", example="Admin")
     *         )
     *      ),
     *    @SWG\Response(response=401, description="Invalid token"),
     *    @SWG\Response(response=201, description="Role created"),
     * )
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(CreateRoleRequest $request)
    {
        return $this->role->create($request->validated());
    }

    /**
     *  @SWG\Get(
     *   path="/role/{id}",
     *   description="Returns the specified role.",
     *      @SWG\Parameter(
     *          name="Authorization",
     *          description="Bearer token, needed for authentication",
     *          required=true,
     *          type="string",
     *          in="header"
     *      ),
     *      @SWG\Parameter(
     *          name="id",
     *          description="The id of the role to be returned.",
     *          required=true,
     *          type="integer",
     *          in="path"
     *      ),
     *   @SWG\Response(response=401, description="Invalid token"),
     *   @SWG\Response(response=404, description="role not found"),
     *   @SWG\Response(response=200, description="Ok"),
     * )
     *
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(int $id)
    {
        return $this->role->fetchOne($id);
    }

    /**
     * @SWG\Put(
     *   path="/role/{id}",
     *   description="Updates the specified role.",
     *      @SWG\Parameter(
     *          name="Authorization",
     *          description="Bearer token, needed for authentication",
     *          required=true,
     *          type="string",
     *          in="header"
     *      ),
     *      @SWG\Parameter(
     *          name="id",
     *          description="The id of the role to be updated.",
     *          required=true,
     *          type="integer",
     *          in="path"
     *      ),
     *
     *      @SWG\Parameter(
     *          name="title",
     *          description="The role to update",
     *          required=true,
     *          type="string",
     *          in="body",
     *          @SWG\Schema(
     *              @SWG\Property(property="role", type="string", example="Updated Admin")
     *         )
     *      ),
     *   @SWG\Response(response=401, description="Invalid token"),
     *   @SWG\Response(response=404, description="Role not found"),
     *   @SWG\Response(response=422, description="The given data was invalid."),
     *   @SWG\Response(response=200, description="Updated"),
     * )
     *
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateRoleRequest $request, $id)
    {
        return $this->role->update($request->validated(), $id);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(int $id)
    {
        //
    }
}
