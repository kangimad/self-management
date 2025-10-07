<?php

namespace App\Repositories;

use App\Models\FinanceCategory;

class FinanceCategoryRepository
{
    public function all()
    {
        return FinanceCategory::orderBy('id', 'desc')->get();
    }

    public function find($id)
    {
        return FinanceCategory::findOrFail($id);
    }

    public function create(array $data)
    {
        return FinanceCategory::create($data);
    }

    public function update($id, array $data)
    {
        $model = FinanceCategory::findOrFail($id);
        $model->update($data);
        return $model;
    }

    public function delete($id)
    {
        return FinanceCategory::destroy($id);
    }
}
