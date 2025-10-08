<?php

namespace App\Services;

use App\Models\Finance\FinanceCategoryType;
use App\Models\Finance\FinanceSourceType;
use App\Repositories\FinanceCategoryTypeRepository;
use Exception;

class FinanceCategoryTypeService
{
    protected $financeCategoryTypeRepository;

    public function __construct(FinanceCategoryTypeRepository $financeCategoryTypeRepository)
    {
        $this->financeCategoryTypeRepository = $financeCategoryTypeRepository;
    }

    public function getAll()
    {
        return $this->financeCategoryTypeRepository->all();
    }

    public function datatable($request)
    {
        try {
            $draw = $request->get('draw', 1);
            $start = $request->get('start', 0);
            $length = $request->get('length', 10);
            $searchValue = $request->get('search')['value'] ?? '';

            $query = $this->financeCategoryTypeRepository->datatable();

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
                        3 => 'categories', // This will be handled specially
                        4 => 'created_at',
                        5 => null, // actions column - not sortable
                    ];

                    if (isset($columns[$columnIndex]) && $columns[$columnIndex] !== null) {
                        $columnName = $columns[$columnIndex];

                        if ($columnName === 'categories') {
                            // For categories column, order by the count of categories
                            $query->withCount('categories')->orderBy('categories_count', $direction);
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
            $totalRecords = FinanceCategoryType::count();

            // Get filtered records count
            $filteredQuery = clone $query;
            $filteredRecords = $filteredQuery->count();

            // Apply pagination
            $categoryTypes = $query->offset($start)->limit($length)->get();

            $data = [];
            foreach ($categoryTypes as $index => $categoryType) {
                $categories = $categoryType->categories->map(function ($category) {
                    return '<span class="badge badge-light-primary fs-7 m-1">' . $category->name . '</span>';
                })->implode(' ');

                if (empty($categories)) {
                    $categories = '<span class="text-muted">Tidak ada kategori</span>';
                }

                $actions = view('dashboard.pages.finances.category-types.partials.actions', compact('categoryType'))->render();

                $data[] = [
                    'id' => $categoryType->id,
                    'name' => $categoryType->name,
                    'description' => $categoryType->description,
                    'categories' => $categories,
                    'created_at' => $categoryType->created_at ? $categoryType->created_at->format('d M Y, H:i') : '-',
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
        return $this->financeCategoryTypeRepository->find($id);
    }

    public function create(array $data)
    {
        // Check if data already exists
        if ($this->financeCategoryTypeRepository->existsByName($data['name'])) {
            throw new Exception('Nama tersebut sudah ada.');
        }
        return $this->financeCategoryTypeRepository->create($data);
    }

    public function update(FinanceCategoryType $result, array $data): bool
    {
        // Check if data already exists (excluding current data)
        if ($this->financeCategoryTypeRepository->existsByName($data['name'], $result->id)) {
            throw new Exception('Nama tersebut sudah ada.');
        }

        return $this->financeCategoryTypeRepository->update($result, $data);
    }

    public function delete(FinanceCategoryType $result): bool
    {
        // Check if result is assigned to any categories
        if ($result->categories()->count() > 0) {
            throw new Exception('Data tidak dapat dihapus karena masih digunakan oleh kategori.');
        }

        return $this->financeCategoryTypeRepository->delete($result);
    }

    public function deleteMultiple(array $ids): bool
    {
        // Check if any result are assigned to categories
        $result = FinanceCategoryType::whereIn('id', $ids)->with('categories')->get();

        $assignedResult = $result->filter(function ($financeCategoryType) {
            return $financeCategoryType->categories()->count() > 0;
        });

        if ($assignedResult->count() > 0) {
            $names = $assignedResult->pluck('name')->implode(', ');
            throw new Exception("Data berikut tidak dapat dihapus karena masih digunakan oleh kategori: {$names}");
        }

        return $this->financeCategoryTypeRepository->deleteMultiple($ids);
    }
}
