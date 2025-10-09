<?php

namespace App\Repositories;

use App\Models\Finance\FinanceSource;

class FinanceSourceRepository
{
    public function all()
    {
        return FinanceSource::orderBy('name', 'desc')->get();
    }

    public function datatable()
    {
        return FinanceSource::query()->with(['sourceType:id,name', 'user:id,name']);
    }

    public function find($id)
    {
        return FinanceSource::findOrFail($id);
    }

    public function create(array $data)
    {
        return FinanceSource::create([
            'name' => $data['name'],
            'description' => $data['description'] ?? null,
            'source_type_id' => $data['source_type_id'],
            'user_id' => $data['user_id'],
        ]);
    }

    public function update(FinanceSource $result, array $data): bool
    {
        return $result->update([
            'name' => $data['name'],
            'description' => $data['description'] ?? null,
            'source_type_id' => $data['source_type_id'],
            'user_id' => $data['user_id'],
        ]);
    }

    public function delete(FinanceSource $result): bool
    {
        return $result->delete();
    }

    public function deleteMultiple(array $ids): bool
    {
        return FinanceSource::whereIn('id', $ids)->delete();
    }

    public function existsByName(string $name, ?int $excludeId = null): bool
    {
        $query = FinanceSource::where('name', $name);

        if ($excludeId) {
            $query->where('id', '!=', $excludeId);
        }

        return $query->exists();
    }
}
