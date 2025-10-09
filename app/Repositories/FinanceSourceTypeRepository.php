<?php

namespace App\Repositories;

use App\Models\Finance\FinanceSourceType;

class FinanceSourceTypeRepository
{
    public function all()
    {
        return FinanceSourceType::orderBy('id', 'desc')->get();
    }

    public function datatable()
    {
        return FinanceSourceType::query()->with(['sources' => function ($query) {
            $query->select('id', 'name', 'source_type_id');
        }]);
    }

    public function find($id)
    {
        return FinanceSourceType::findOrFail($id);
    }

    public function create(array $data)
    {
        return FinanceSourceType::create([
            'name' => $data['name'],
            'description' => $data['description'] ?? null,
        ]);
    }

    public function update(FinanceSourceType $result, array $data): bool
    {
        return $result->update([
            'name' => $data['name'],
            'description' => $data['description'] ?? null,
        ]);
    }

    public function delete(FinanceSourceType $result): bool
    {
        return $result->delete();
    }

    public function deleteMultiple(array $ids): bool
    {
        return FinanceSourceType::whereIn('id', $ids)->delete();
    }

    public function existsByName(string $name, ?int $excludeId = null): bool
    {
        $query = FinanceSourceType::where('name', $name);

        if ($excludeId) {
            $query->where('id', '!=', $excludeId);
        }

        return $query->exists();
    }
}
