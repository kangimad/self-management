<?php

namespace App\Repositories;

use App\Models\Task;

class TaskRepository
{
    public function all()
    {
        return Task::orderBy('id', 'desc')->get();
    }

    public function find($id)
    {
        return Task::findOrFail($id);
    }

    public function create(array $data)
    {
        return Task::create($data);
    }

    public function update($id, array $data)
    {
        $model = Task::findOrFail($id);
        $model->update($data);
        return $model;
    }

    public function delete($id)
    {
        return Task::destroy($id);
    }
}
