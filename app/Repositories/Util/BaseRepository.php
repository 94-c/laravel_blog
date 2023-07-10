<?php

namespace App\Repositories\Util;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;

class BaseRepository implements EloquentRepositoryInterface
{

    protected Model $model;

    public function __construct(Model $model)
    {
        $this->model = $model;
    }

    public function getQueryBuilder(): \Illuminate\Database\Eloquent\Builder
    {
        return $this->model->query();
    }

    public function findById(int $id, array $columns = ['*'], array $relations = []): Model
    {
        return $this->model->find($id, $columns);
    }

    public function all()
    {
        return $this->model->all();
    }

    public function searchKeyword(string $type, string $keyword)
    {
        return $this->model->searchKeyword($type, $keyword);
    }

    public function create(array $attributes): Model
    {
        return $this->model->create($attributes);
    }

    public function update(array $attributes): bool
    {
        return $this->model->update($attributes);
    }

    public function delete(int $id): bool
    {
        $model = $this->findById($id);

        return $model->delete();
    }


}
