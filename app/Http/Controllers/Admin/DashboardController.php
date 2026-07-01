<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Beasiswa;
use App\Models\User;

class DashboardController extends Controller
{
    public function index()
    {
        return view('admin.dashboard', [
            'totalUser' => User::count(),
            'totalMahasiswa' => User::where('role', 'mahasiswa')->count(),
            'totalAdmin' => User::where('role', 'admin')->count(),
            'totalBeasiswa' => Beasiswa::count(),
        ]);
    }
}