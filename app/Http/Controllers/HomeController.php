<?php

namespace App\Http\Controllers;

class HomeController extends Controller
{
    public function index()
    {
        $scholarships = [

            [
                'title' => 'Beasiswa LPDP',
                'provider' => 'LPDP',
                'location' => 'Indonesia',
                'level' => 'S2/S3',
                'deadline' => '30 Juli 2026',
                'status' => 'Dibuka',
                'image' => 'https://upload.wikimedia.org/wikipedia/commons/thumb/5/5f/Logo_LPDP.png/640px-Logo_LPDP.png',
                'link' => 'https://lpdp.kemenkeu.go.id/'
            ],

            [
                'title' => 'Beasiswa Bank Indonesia',
                'provider' => 'Bank Indonesia',
                'location' => 'Indonesia',
                'level' => 'S1',
                'deadline' => '10 Agustus 2026',
                'status' => 'Dibuka',
                'image' => 'https://upload.wikimedia.org/wikipedia/commons/0/00/Bank_Indonesia_logo.svg',
                'link' => 'https://www.bi.go.id/'
            ],

        ];

        return view('home', compact('scholarships'));
    }
}