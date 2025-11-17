<?php

namespace App\Repositories;

use Illuminate\Database\Eloquent\Model;

/**
 * @template TModel of \Illuminate\Database\Eloquent\Model
 */
abstract class BaseRepository
{
    /**
     * @var TModel
     */
    protected Model $model;

    /**
     * @param TModel $model
     */
    public function __construct(Model $model)
    {
        $this->model = $model;
    }

    /**
     * @return Collection<int, TModel>
     */
    public function all()
    {
        return $this->model->all();
    }

    /**
     * @return TModel
     */
    public function find(int $id)
    {
        return $this->model->findOrFail($id);
    }

    /**
     * @param array<string, mixed> $data
     * @return TModel
     */
    public function create(array $data)
    {
        return $this->model->create($data);
    }

    /**
     * @param array<string, mixed> $data
     * @return TModel
     */
    public function update(int $id, array $data)
    {
        $record = $this->find($id);
        $record->update($data);
        return $record;
    }

    /**
     * @return bool|null
     */
    public function delete(int $id)
    {
        return $this->find($id)->delete();
    }
}
