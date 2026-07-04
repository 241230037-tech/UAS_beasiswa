<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Beasiswa;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class BeasiswaController extends Controller
{
    /**
     * Menampilkan daftar beasiswa.
     */
    public function index()
    {
        $beasiswas = Beasiswa::latest()->get();

        return view('admin.beasiswa.index', compact('beasiswas'));
    }

    /**
     * Menampilkan form tambah beasiswa.
     */
    public function create()
    {
        return view('admin.beasiswa.create');
    }

    /**
     * Menyimpan data beasiswa.
     */
    public function store(Request $request)
{
    $request->validate([
        'nama' => 'required|max:255',
        'penyelenggara' => 'required|max:255',
        'deskripsi' => 'required',
        'persyaratan' => 'nullable',
        'minimal_ipk' => 'nullable|numeric',
        'jurusan' => 'nullable|max:255',
        'semester_min' => 'nullable|integer',
        'domisili' => 'nullable|max:255',
        'deadline' => 'required|date',
        'link_pendaftaran' => 'nullable|url',
        'gambar' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
        'status' => 'required|in:dibuka,ditutup',
    ]);

    // nanti di sini upload gambar
$gambar = null;

if ($request->hasFile('gambar')) {

    $gambar = $request->file('gambar')
                      ->store('beasiswa', 'public');

}
    Beasiswa::create([
    'nama' => $request->nama,
    'penyelenggara' => $request->penyelenggara,
    'deskripsi' => $request->deskripsi,
    'persyaratan' => $request->persyaratan,
    'minimal_ipk' => $request->minimal_ipk,
    'jurusan' => $request->jurusan,
    'semester_min' => $request->semester_min,
    'domisili' => $request->domisili,
    'deadline' => $request->deadline,
    'link_pendaftaran' => $request->link_pendaftaran,
    'gambar' => $gambar,
    'status' => $request->status,
    'admin_id' => Auth::id(),
]);

    return redirect()
        ->route('admin.beasiswa.index')
        ->with('success', 'Data beasiswa berhasil ditambahkan.');
}
    /**
     * Menampilkan detail beasiswa.
     */
    public function show(Beasiswa $beasiswa)
    {
        return view('admin.beasiswa.show', compact('beasiswa'));
    }

    /**
     * Menampilkan form edit.
     */
    public function edit(Beasiswa $beasiswa)
    {
        return view('admin.beasiswa.edit', compact('beasiswa'));
    }

    /**
     * Memperbarui data beasiswa.
     */
    public function update(Request $request, Beasiswa $beasiswa)
    {
        $data = $request->validate([
    'nama' => 'required|string|max:255',
    'penyelenggara' => 'required|string|max:255',
    'deskripsi' => 'required',
    'persyaratan' => 'nullable',
    'minimal_ipk' => 'nullable|numeric',
    'jurusan' => 'nullable|string|max:255',
    'semester_min' => 'nullable|integer',
    'domisili' => 'nullable|string|max:255',
    'deadline' => 'required|date',
    'link_pendaftaran' => 'nullable|url',
    'gambar' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
    'status' => 'required|in:dibuka,ditutup',
]);

if ($request->hasFile('gambar')) {

    if ($beasiswa->gambar && Storage::disk('public')->exists($beasiswa->gambar)) {
        Storage::disk('public')->delete($beasiswa->gambar);
    }

    $data['gambar'] = $request->file('gambar')->store('beasiswa', 'public');
}

$beasiswa->update($data);

        return redirect()
            ->route('admin.beasiswa.index')
            ->with('success', 'Data beasiswa berhasil diperbarui.');
    }

    /**
     * Menghapus data beasiswa.
     */
    public function destroy(Beasiswa $beasiswa)
{
    if ($beasiswa->gambar && Storage::disk('public')->exists($beasiswa->gambar)) {

        Storage::disk('public')->delete($beasiswa->gambar);

    }

    $beasiswa->delete();

    return redirect()
        ->route('admin.beasiswa.index')
        ->with('success', 'Data beasiswa berhasil dihapus.');
}
}