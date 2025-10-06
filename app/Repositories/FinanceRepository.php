<?php

namespace App\Repositories;

use App\Models\Finance;

class FinanceRepository
{
    public function all()
    {
        return Finance::orderBy('id', 'desc')->get();
    }

    public function find($id)
    {
        return Finance::findOrFail($id);
    }

    public function create(array $data)
    {
        return Finance::create($data);
    }

    public function update($id, array $data)
    {
        $model = Finance::findOrFail($id);
        $model->update($data);
        return $model;
    }

    public function delete($id)
    {
        return Finance::destroy($id);
    }
}
