<?php

namespace App\Repositories\Eloquent;

use App\Repositories\Util\EloquentRepositoryInterface;
use App\Role;

interface RoleRepositoryInterface extends EloquentRepositoryInterface
{
    public function findAllRole();

    public function findUsersByRole(Role $role);

}
