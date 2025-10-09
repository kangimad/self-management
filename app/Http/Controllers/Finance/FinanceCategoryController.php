<?php

namespace App\Http\Controllers\Finance;

use Exception;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use App\Http\Controllers\Controller;
use App\Models\Finance\FinanceCategory;
use App\Services\FinanceCategoryService;
use App\Http\Requests\FInance\FinanceCategoryStoreRequest;
use App\Http\Requests\FInance\FinanceCategoryUpdateRequest;

class FinanceCategoryController extends Controller
{
    protected $financeCategoryService;
    public function __construct(FinanceCategoryService $financeCategoryService)
    {
        $this->financeCategoryService = $financeCategoryService;
    }

    public function index(Request $request)
    {
        $metadata = [
            'title' => 'Finance Category',
            'desc' => 'Halaman yang berisi summary finance aplikasi.',
            'bread1' => '<i class="ki-outline ki-home text-gray-700 fs-6"></i>',
            'bread1_link' => route('dashboard'),
            'bread2' => 'Finance',
            'bread2_link' => route('finance.index'),
            'bread3' => 'Category',
            'bread3_link' => route('finance.categories.index'),
            'bread4' => '',
            'bread4_link' => '',
            'bread5' => '',
            'bread5_link' => '',
            'page' => 'Daftar',
        ];

        $types=$this->financeCategoryService->getAvailableFinanceCategoryTypes();

        return view('dashboard.pages.finances.categories.index', compact('metadata', 'types'));
    }

    public function datatable(Request $request): JsonResponse
    {
        $data = $this->financeCategoryService->datatable($request);

        if (isset($data['error'])) {
            return response()->json($data, 500);
        }

        return response()->json($data);
    }

    public function store(FinanceCategoryStoreRequest $request): JsonResponse
    {
        try {
            $data = $this->financeCategoryService->create($request->validated());

            return response()->json([
                'success' => true,
                'message' => 'Data berhasil dibuat.',
                'data' => $data
            ]);
        } catch (Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], 422);
        }
    }

    public function show(FinanceCategory $result): JsonResponse
    {
        $result->load('categoryType', 'user');

        return response()->json([
            'success' => true,
            'data' => $result
        ]);
    }

    public function update(FinanceCategoryUpdateRequest $request, FinanceCategory $result): JsonResponse
    {
        try {
            $this->financeCategoryService->update($result, $request->validated());

            return response()->json([
                'success' => true,
                'message' => 'Data berhasil diupdate.',
                'data' => $result->fresh()
            ]);
        } catch (Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], 422);
        }
    }

    public function destroy(FinanceCategory $result): JsonResponse
    {
        try {
            $this->financeCategoryService->delete($result);

            return response()->json([
                'success' => true,
                'message' => 'Data berhasil dihapus.'
            ]);
        } catch (Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], 422);
        }
    }

    public function destroyMultiple(Request $request): JsonResponse
    {
        $request->validate([
            'ids' => 'required|array',
            'ids.*' => 'exists:finance_categories,id'
        ]);

        try {
            $this->financeCategoryService->deleteMultiple($request->ids);

            return response()->json([
                'success' => true,
                'message' => 'Data berhasil dihapus.'
            ]);
        } catch (Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], 422);
        }
    }
}
