<?php

namespace App\Services;

use Exception;
use App\Models\Finance\FinanceCategory;
use App\Repositories\FinanceCategoryRepository;

class FinanceCategoryService
{
    protected $financeCategoryRepository;

    public function __construct(FinanceCategoryRepository $financeCategoryRepository)
    {
        $this->financeCategoryRepository = $financeCategoryRepository;
    }

    public function getAll()
    {
        return $this->financeCategoryRepository->all();
    }

    public function datatable($request)
    {
        try {
            $draw = $request->get('draw', 1);
            $start = $request->get('start', 0);
            $length = $request->get('length', 10);
            $searchValue = $request->get('search')['value'] ?? '';

            $query = $this->financeCategoryRepository->datatable();

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
                        3 => 'categoryType', // This will be handled specially
                        4 => 'created_at',
                        5 => null, // actions column - not sortable
                    ];

                    if (isset($columns[$columnIndex]) && $columns[$columnIndex] !== null) {
                        $columnName = $columns[$columnIndex];

                        if ($columnName === 'categoryType') {
                            // For categoryType column, order by the count of categories
                            $query->withCount('categoryType')->orderBy('categoryType_count', $direction);
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
            $totalRecords = FinanceCategory::count();

            // Get filtered records count
            $filteredQuery = clone $query;
            $filteredRecords = $filteredQuery->count();

            // Apply pagination
            $results = $query->offset($start)->limit($length)->get();

            $data = [];
            foreach ($results as $index => $result) {
                // Category Type
                $categoryType = $result->categoryType
                    ? '<span class="badge badge-light-primary fs-7 m-1">' . e($result->categoryType->name) . '</span>'
                    : '<span class="text-muted">Tidak ada type</span>';

                // User
                $user = $result->user
                    ? '<span class="badge badge-light-success fs-7 m-1">' . e($result->user->name) . '</span>'
                    : '<span class="text-muted">Tidak ada user</span>';

                // Actions
                $actions = view('dashboard.pages.finances.categories.partials.actions', compact('result'))->render();

                $data[] = [
                    'id' => $result->id,
                    'name' => e($result->name),
                    'description' => e($result->description),
                    'categoryType' => $categoryType,
                    'user' => $user,
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
        return $this->financeCategoryRepository->find($id);
    }

    public function create(array $data)
    {
        // Check if data already exists
        if ($this->financeCategoryRepository->existsByName($data['name'])) {
            throw new Exception('Nama tersebut sudah ada.');
        }
        return $this->financeCategoryRepository->create($data);
    }

    public function update(FinanceCategory $result, array $data): bool
    {
        // Check if data already exists (excluding current data)
        if ($this->financeCategoryRepository->existsByName($data['name'], $result->id)) {
            throw new Exception('Nama tersebut sudah ada.');
        }

        return $this->financeCategoryRepository->update($result, $data);
    }

    public function delete(FinanceCategory $result): bool
    {
        return $this->financeCategoryRepository->delete($result);
    }

    public function deleteMultiple(array $ids): bool
    {
        return $this->financeCategoryRepository->deleteMultiple($ids);
    }

    public function getAvailableFinanceCategoryTypes()
    {
        // Assuming you have a FinanceCategoryType model and repository
        return \App\Models\Finance\FinanceCategoryType::orderBy('name', 'asc')->get();
    }
}
