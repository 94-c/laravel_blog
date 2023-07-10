<?php

namespace App\Repositories\Util;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;

interface EloquentRepositoryInterface
{

    public function findById(int $id, array $columns = ['*'], array $relations = []) : Model;

    public function all();

    public function create(array $attributes) : Model;

    public function update(array $attributes) : bool;

}
