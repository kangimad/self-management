<?php

namespace App\Repositories;

use App\Models\Event;

class EventRepository
{
    public function all()
    {
        return Event::orderBy('id', 'desc')->get();
    }

    public function find($id)
    {
        return Event::findOrFail($id);
    }

    public function create(array $data)
    {
        return Event::create($data);
    }

    public function update($id, array $data)
    {
        $model = Event::findOrFail($id);
        $model->update($data);
        return $model;
    }

    public function delete($id)
    {
        return Event::destroy($id);
    }
}
