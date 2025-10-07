<?php

namespace App\Repositories;

use App\Models\Finance\FinanceCategoryType;

class FinanceCategoryTypeRepository
{
    public function all()
    {
        return FinanceCategoryType::orderBy('id', 'desc')->get();
    }

    public function datatable()
    {
        return FinanceCategoryType::query()->with(['categories' => function ($query) {
            $query->select('id', 'name', 'category_type_id');
        }]);
    }

    public function find($id)
    {
        return FinanceCategoryType::findOrFail($id);
    }

    public function create(array $data)
    {
        return FinanceCategoryType::create($data);
    }

    public function update($id, array $data)
    {
        $model = FinanceCategoryType::findOrFail($id);
        $model->update($data);
        return $model;
    }

    public function delete($id)
    {
        return FinanceCategoryType::destroy($id);
    }
}
