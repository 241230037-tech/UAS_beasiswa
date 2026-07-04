<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Beasiswa;

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