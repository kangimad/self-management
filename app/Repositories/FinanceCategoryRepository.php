<?php

namespace App\Repositories;

use App\Models\Finance\FinanceCategory;

class FinanceCategoryRepository
{
    public function all()
    {
        return FinanceCategory::orderBy('name', 'desc')->get();
    }

    public function datatable()
    {
        return FinanceCategory::query()->with(['categoryType:id,name', 'user:id,name']);
    }

    public function find($id)
    {
        return FinanceCategory::findOrFail($id);
    }

    public function create(array $data)
    {
        return FinanceCategory::create([
            'name' => $data['name'],
            'description' => $data['description'] ?? null,
            'category_type_id' => $data['category_type_id'],
            'user_id' => $data['user_id'],
        ]);
    }

    public function update(FinanceCategory $result, array $data): bool
    {
        return $result->update([
            'name' => $data['name'],
            'description' => $data['description'] ?? null,
            'category_type_id' => $data['category_type_id'],
            'user_id' => $data['user_id'],
        ]);
    }

    public function delete(FinanceCategory $result): bool
    {
        return $result->delete();
    }

    public function deleteMultiple(array $ids): bool
    {
        return FinanceCategory::whereIn('id', $ids)->delete();
    }

    public function existsByName(string $name, ?int $excludeId = null): bool
    {
        $query = FinanceCategory::where('name', $name);

        if ($excludeId) {
            $query->where('id', '!=', $excludeId);
        }

        return $query->exists();
    }
}
