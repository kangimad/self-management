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
        return FinanceCategoryType::create([
            'name' => $data['name'],
            'description' => $data['description'] ?? null,
        ]);
    }

    public function update(FinanceCategoryType $result, array $data): bool
    {
        return $result->update([
            'name' => $data['name'],
            'description' => $data['description'] ?? null,
        ]);
    }

    public function delete(FinanceCategoryType $result): bool
    {
        return $result->delete();
    }

    public function deleteMultiple(array $ids): bool
    {
        return FinanceCategoryType::whereIn('id', $ids)->delete();
    }

    public function existsByName(string $name, ?int $excludeId = null): bool
    {
        $query = FinanceCategoryType::where('name', $name);

        if ($excludeId) {
            $query->where('id', '!=', $excludeId);
        }

        return $query->exists();
    }
}
