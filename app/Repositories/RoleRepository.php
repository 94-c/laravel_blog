<?php

namespace App\Repositories;

use App\Repositories\Eloquent\RoleRepositoryInterface;
use App\Repositories\Util\BaseRepository;
use App\Role;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;

class RoleRepository extends BaseRepository implements RoleRepositoryInterface
{

    public function __construct(Role $role)
    {
        parent::__construct($role);
    }

    public function findAllRole()
    {
        return $this->model->all();
    }

    public function findUsersByRole(Role $role): Collection
    {
        return $role->users()
            ->select('users.id', 'users.name')->get();
    }


}
