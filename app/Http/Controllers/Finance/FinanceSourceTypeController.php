<?php

namespace App\Http\Controllers\Finance;

use Exception;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use App\Http\Controllers\Controller;
use App\Models\Finance\FinanceSourceType;
use App\Services\FinanceSourceTypeService;
use App\Http\Requests\Finance\FinanceSourceTypeStoreRequest;
use App\Http\Requests\Finance\FinanceSourceTypeUpdateRequest;

class FinanceSourceTypeController extends Controller
{
    protected $financeSourceTypeService;
    public function __construct(FinanceSourceTypeService $financeSourceTypeService)
    {
        $this->financeSourceTypeService = $financeSourceTypeService;
    }

    public function index(Request $request)
    {
        $metadata = [
            'title' => 'Finance Source Types',
            'desc' => 'Halaman yang berisi summary finance aplikasi.',
            'bread1' => '<i class="ki-outline ki-home text-gray-700 fs-6"></i>',
            'bread1_link' => route('dashboard'),
            'bread2' => 'Finance',
            'bread2_link' => route('finance.index'),
            'bread3' => 'Source Types',
            'bread3_link' => route('finance.source-types.index'),
            'bread4' => '',
            'bread4_link' => '',
            'bread5' => '',
            'bread5_link' => '',
            'page' => 'Daftar',
        ];

        return view('dashboard.pages.finances.source-types.index', compact('metadata'));
    }

    public function datatable(Request $request): JsonResponse
    {
        $data = $this->financeSourceTypeService->datatable($request);

        if (isset($data['error'])) {
            return response()->json($data, 500);
        }

        return response()->json($data);
    }

    public function store(FinanceSourceTypeStoreRequest $request): JsonResponse
    {
        try {
            $data = $this->financeSourceTypeService->create($request->validated());

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

    public function show(FinanceSourceType $result): JsonResponse
    {
        $result->load('sources');

        return response()->json([
            'success' => true,
            'data' => $result
        ]);
    }

    public function update(FinanceSourceTypeUpdateRequest $request, FinanceSourceType $result): JsonResponse
    {
        try {
            $this->financeSourceTypeService->update($result, $request->validated());

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

    public function destroy(FinanceSourceType $result): JsonResponse
    {
        try {
            $this->financeSourceTypeService->delete($result);

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
            'ids.*' => 'exists:finance_source_types,id'
        ]);

        try {
            $this->financeSourceTypeService->deleteMultiple($request->ids);

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
