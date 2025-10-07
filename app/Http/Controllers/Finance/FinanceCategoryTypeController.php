<?php

namespace App\Http\Controllers\Finance;

use App\Http\Controllers\Controller;
use App\Services\FinanceCategoryTypeService;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
class FinanceCategoryTypeController extends Controller
{
    protected $financeCategoryTypeService;
    public function __construct(FinanceCategoryTypeService $financeCategoryTypeService)
    {
        $this->financeCategoryTypeService = $financeCategoryTypeService;
    }

    /**
     * Display a listing of the resource.
     */
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
}
