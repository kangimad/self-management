<?php

namespace App\Http\Controllers\Finance;

use Exception;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use App\Http\Controllers\Controller;
use App\Models\Finance\FinanceSource;
use App\Services\FinanceSourceService;
use App\Http\Requests\Finance\FinanceSourceStoreRequest;
use App\Http\Requests\Finance\FinanceSourceUpdateRequest;

class FinanceSourceController extends Controller
{
    protected $financeSourceService;
    public function __construct(FinanceSourceService $financeSourceService)
    {
        $this->financeSourceService = $financeSourceService;
    }

    public function index(Request $request)
    {
        $metadata = [
            'title' => 'Finance Source',
            'desc' => 'Halaman yang berisi summary finance aplikasi.',
            'bread1' => '<i class="ki-outline ki-home text-gray-700 fs-6"></i>',
            'bread1_link' => route('dashboard'),
            'bread2' => 'Finance',
            'bread2_link' => route('finance.index'),
            'bread3' => 'Source',
            'bread3_link' => route('finance.sources.index'),
            'bread4' => '',
            'bread4_link' => '',
            'bread5' => '',
            'bread5_link' => '',
            'page' => 'Daftar',
        ];

        $types=$this->financeSourceService->getAvailableFinanceSourceTypes();

        return view('dashboard.pages.finances.sources.index', compact('metadata', 'types'));
    }

    public function datatable(Request $request): JsonResponse
    {
        $data = $this->financeSourceService->datatable($request);

        if (isset($data['error'])) {
            return response()->json($data, 500);
        }

        return response()->json($data);
    }

    public function store(FinanceSourceStoreRequest $request): JsonResponse
    {
        try {
            $data = $this->financeSourceService->create($request->validated());

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

    public function show(FinanceSource $result): JsonResponse
    {
        $result->load('sourceType', 'user');

        return response()->json([
            'success' => true,
            'data' => $result
        ]);
    }

    public function update(FinanceSourceUpdateRequest $request, FinanceSource $result): JsonResponse
    {
        try {
            $this->financeSourceService->update($result, $request->validated());

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

    public function destroy(FinanceSource $result): JsonResponse
    {
        try {
            $this->financeSourceService->delete($result);

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
            'ids.*' => 'exists:finance_sources,id'
        ]);

        try {
            $this->financeSourceService->deleteMultiple($request->ids);

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
