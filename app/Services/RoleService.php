<?php

namespace App\Services;

use App\Exceptions\NotFoundCustomException;
use App\Repositories\Eloquent\RoleRepositoryInterface;
use App\Repositories\RoleRepository;
use App\Role;

class RoleService
{
    private RoleRepositoryInterface $roleRepository;

    public function __construct(RoleRepositoryInterface $roleRepository)
    {
        $this->roleRepository = $roleRepository;
    }

    /**
     * @throws NotFoundCustomException
     */
    public function findAllRole()
    {
        $roles = $this->roleRepository->findAllRole();

        if ($roles->isEmpty()) {
            throw new NotFoundCustomException();
        }

        return $roles;
    }

    public function findUsersByRole(Role $role): \Illuminate\Database\Eloquent\Collection
    {
        return $this->roleRepository->findUsersByRole($role);
    }


}
