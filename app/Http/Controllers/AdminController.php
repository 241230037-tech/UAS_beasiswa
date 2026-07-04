<?php

namespace App\Http\Controllers;

use App\Models\Beasiswa;
use App\Models\User;

class AdminController extends Controller
{
    public function dashboard()
    {
        $totalBeasiswa = Beasiswa::count();
        $totalPengguna = User::count();

        return view('admin.dashboard', compact(
            'totalBeasiswa',
            'totalPengguna'
        ));
    }
}