<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index(Request $request)
    {
        $metadata = [
            'title' => 'Dashboard',
            'desc' => 'Halaman yang berisi daftar dashboard aplikasi.',
            'bread1' => '<i class="ki-outline ki-home text-gray-700 fs-6"></i>',
            'bread1_link' => route('dashboard'),
            'bread2' => '',
            'bread2_link' => '',
            'bread3' => '',
            'bread3_link' => '',
            'bread4' => '',
            'bread4_link' => '',
            'bread5' => '',
            'bread5_link' => '',
            'page' => 'Dashboard',
        ];

        return view('dashboard.index', compact('metadata'));
    }
}
