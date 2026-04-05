<?php

namespace App\Repositories;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;

abstract class BaseRepository
{
    protected Model $model;

    public function __construct(Model $model)
    {
        $this->model = $model;
    }

    public function all(): Collection
    {
        return $this->model->all();
    }

    public function create(Model $attributes): Model
    {
        return $this->model->create($attributes->getAttributes());
    }

    public function update(int $id, Model $attributes): Model
    {
        $recode = $this->model->find($id);
        $recode->update($attributes->getAttributes());
        return $recode;
    }

    public function find(int $id): Model
    {
        return $this->model->findOrFail($id);
    }

    public function delete(int $id): bool
    {
        return $this->model->findOrFail($id)->delete();
    }
}
