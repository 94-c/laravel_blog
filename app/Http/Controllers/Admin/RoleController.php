<?php

namespace App\Http\Controllers\Admin;

use App\Exceptions\NotFoundCustomException;
use App\Http\Controllers\Controller;
use App\Http\Resources\RoleResource;
use App\Role;
use App\Services\RoleService;
use Symfony\Component\HttpFoundation\Response;

class RoleController extends Controller
{
    private RoleService $roleService;

    public function __construct(RoleService $roleService)
    {
        $this->roleService = $roleService;
    }

    /**
     * @throws NotFoundCustomException
     */
    public function findAllRoles()
    {
        $roles = $this->roleService->findAllRole();

        return response(new RoleResource($roles), Response::HTTP_OK);
    }

    public function findUsersByRole(Role $role)
    {
        $users = $this->roleService->findUsersByRole($role);

        return response(new RoleResource($users), Response::HTTP_OK);
    }
}
