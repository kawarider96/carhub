<?php

namespace App\Repositories;

use Illuminate\Database\Eloquent\Model;

abstract class BaseRepository
{
    protected Model $model;

    public function __construct(Model $model)
    {
        $this->model = $model;
    }

    /**
     * @return \Illuminate\Database\Eloquent\Collection<int, Model>
     */
    public function all()
    {
        return $this->model->all();
    }

    /**
     * @return Model
     */
    public function find(int $id)
    {
        return $this->model->findOrFail($id);
    }

    /**
     * @param array<string, mixed> $data
     * @return Model
     */
    public function create(array $data)
    {
        return $this->model->create($data);
    }

    /**
     * @param int $id
     * @param array<string, mixed> $data
     * @return Model
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
