<?php

namespace App\Http\Controllers\Finance;

use Exception;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use App\Http\Controllers\Controller;
use App\Models\Finance\FinanceCategoryType;
use App\Services\FinanceCategoryTypeService;
use App\Http\Requests\Finance\FinanceCategoryTypeStoreRequest;
use App\Http\Requests\Finance\FinanceCategoryTypeUpdateRequest;
use App\Models\Finance\FinanceTransactionType;

class FinanceCategoryTypeController extends Controller
{
    protected $financeCategoryTypeService;
    public function __construct(FinanceCategoryTypeService $financeCategoryTypeService)
    {
        $this->financeCategoryTypeService = $financeCategoryTypeService;
    }

    public function index(Request $request)
    {
        $metadata = [
            'title' => 'Finance Category Types',
            'desc' => 'Halaman yang berisi summary finance aplikasi.',
            'bread1' => '<i class="ki-outline ki-home text-gray-700 fs-6"></i>',
            'bread1_link' => route('dashboard'),
            'bread2' => 'Finance',
            'bread2_link' => route('finance.index'),
            'bread3' => 'Category Types',
            'bread3_link' => route('finance.category-types.index'),
            'bread4' => '',
            'bread4_link' => '',
            'bread5' => '',
            'bread5_link' => '',
            'page' => 'Daftar',
        ];

        return view('dashboard.pages.finances.category-types.index', compact('metadata'));
    }

    public function datatable(Request $request): JsonResponse
    {
        $data = $this->financeCategoryTypeService->datatable($request);

        if (isset($data['error'])) {
            return response()->json($data, 500);
        }

        return response()->json($data);
    }

    public function store(FinanceCategoryTypeStoreRequest $request): JsonResponse
    {
        try {
            $data = $this->financeCategoryTypeService->create($request->validated());

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

    public function show(FinanceCategoryType $result): JsonResponse
    {
        $result->load('categories');

        return response()->json([
            'success' => true,
            'data' => $result
        ]);
    }

    public function update(FinanceCategoryTypeUpdateRequest $request, FinanceCategoryType $result): JsonResponse
    {
        try {
            $this->financeCategoryTypeService->update($result, $request->validated());

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

    public function destroy(FinanceCategoryType $result): JsonResponse
    {
        try {
            $this->financeCategoryTypeService->delete($result);

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
            'ids.*' => 'exists:finance_category_types,id'
        ]);

        try {
            $this->financeCategoryTypeService->deleteMultiple($request->ids);

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
