<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class SettingController extends Controller
{
    public function index()
    {
        $metadata = [
            'title' => 'Setting',
            'desc' => 'Halaman yang berisi pengaturan aplikasi.',
            'bread1' => '<i class="ki-outline ki-home text-gray-700 fs-6"></i>',
            'bread1_link' => route('dashboard'),
            'bread2' => 'Dashboard',
            'bread2_link' => route('dashboard'),
            'bread3' => '',
            'bread3_link' => '',
            'bread4' => '',
            'bread4_link' => '',
            'bread5' => '',
            'bread5_link' => '',
            'page' => 'Setting',
        ];
        return view('dashboard.pages.settings.index',compact('metadata'));
    }
}
