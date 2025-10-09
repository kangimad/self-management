<?php

namespace App\Services;

use Exception;
use App\Models\Finance\FinanceSourceType;
use App\Repositories\FinanceSourceTypeRepository;

class FinanceSourceTypeService
{
    protected $financeSourceTypeRepository;

    public function __construct(FinanceSourceTypeRepository $financeSourceTypeRepository)
    {
        $this->financeSourceTypeRepository = $financeSourceTypeRepository;
    }

    public function getAll()
    {
        return $this->financeSourceTypeRepository->all();
    }

    public function datatable($request)
    {
        try {
            $draw = $request->get('draw', 1);
            $start = $request->get('start', 0);
            $length = $request->get('length', 10);
            $searchValue = $request->get('search')['value'] ?? '';

            $query = $this->financeSourceTypeRepository->datatable();

            // Apply search filter
            if (!empty($searchValue)) {
                $query->where('name', 'like', "%{$searchValue}%");
            }

            // Get ordering data from request
            $orderData = $request->get('order', []);

            // Apply ordering
            $orderApplied = false;
            if (!empty($orderData)) {
                foreach ($orderData as $order) {
                    $columnIndex = intval($order['column']);
                    $direction = strtolower($order['dir']) === 'desc' ? 'desc' : 'asc';

                    // Map column indices to actual column names
                    $columns = [
                        0 => null, // checkbox column - not sortable
                        1 => null, // index column - not sortable
                        2 => 'name',
                        3 => 'sources', // This will be handled specially
                        4 => 'created_at',
                        5 => null, // actions column - not sortable
                    ];

                    if (isset($columns[$columnIndex]) && $columns[$columnIndex] !== null) {
                        $columnName = $columns[$columnIndex];

                        if ($columnName === 'sources') {
                            // For sources column, order by the count of sources
                            $query->withCount('sources')->orderBy('sources_count', $direction);
                        } else {
                            $query->orderBy($columnName, $direction);
                        }
                        $orderApplied = true;
                        break; // Apply only the first valid order
                    }
                }
            }

            // Default ordering by name if no order was applied
            if (!$orderApplied) {
                $query->orderBy('name', 'asc');
            }

            // Get total records count
            $totalRecords = FinanceSourceType::count();

            // Get filtered records count
            $filteredQuery = clone $query;
            $filteredRecords = $filteredQuery->count();

            // Apply pagination
            $results = $query->offset($start)->limit($length)->get();

            $data = [];
            foreach ($results as $index => $result) {
                $sources = $result->sources->map(function ($source) {
                    return '<span class="badge badge-light-primary fs-7 m-1">' . $source->name . '</span>';
                })->implode(' ');

                if (empty($sources)) {
                    $sources = '<span class="text-muted">Tidak ada kategori</span>';
                }

                $actions = view('dashboard.pages.finances.source-types.partials.actions', compact('result'))->render();

                $data[] = [
                    'id' => $result->id,
                    'name' => $result->name,
                    'description' => $result->description,
                    'sources' => $sources,
                    'created_at' => $result->created_at ? $result->created_at->format('d M Y, H:i') : '-',
                    'actions' => $actions
                ];
            }

            return [
                'draw' => intval($draw),
                'recordsTotal' => $totalRecords,
                'recordsFiltered' => $filteredRecords,
                'data' => $data
            ];
        } catch (Exception $e) {
            return [
                'draw' => $request->get('draw', 1),
                'recordsTotal' => 0,
                'recordsFiltered' => 0,
                'data' => [],
                'error' => $e->getMessage()
            ];
        }
    }

    public function find($id)
    {
        return $this->financeSourceTypeRepository->find($id);
    }

    public function create(array $data)
    {
        // Check if data already exists
        if ($this->financeSourceTypeRepository->existsByName($data['name'])) {
            throw new Exception('Nama tersebut sudah ada.');
        }
        return $this->financeSourceTypeRepository->create($data);
    }

    public function update(FinanceSourceType $result, array $data): bool
    {
        // Check if data already exists (excluding current data)
        if ($this->financeSourceTypeRepository->existsByName($data['name'], $result->id)) {
            throw new Exception('Nama tersebut sudah ada.');
        }

        return $this->financeSourceTypeRepository->update($result, $data);
    }

    public function delete(FinanceSourceType $result): bool
    {
        // Check if result is assigned to any sources
        if ($result->sources()->count() > 0) {
            throw new Exception('Data tidak dapat dihapus karena masih digunakan oleh kategori.');
        }

        return $this->financeSourceTypeRepository->delete($result);
    }

    public function deleteMultiple(array $ids): bool
    {
        // Check if any result are assigned to sources
        $result = FinanceSourceType::whereIn('id', $ids)->with('sources')->get();

        $assignedResult = $result->filter(function ($financeSourceType) {
            return $financeSourceType->sources()->count() > 0;
        });

        if ($assignedResult->count() > 0) {
            $names = $assignedResult->pluck('name')->implode(', ');
            throw new Exception("Data berikut tidak dapat dihapus karena masih digunakan oleh kategori: {$names}");
        }

        return $this->financeSourceTypeRepository->deleteMultiple($ids);
    }
}
