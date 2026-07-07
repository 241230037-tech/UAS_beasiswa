<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Iklan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class IklanController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $iklans = Iklan::latest()->get();

        return view('admin.iklan.index', compact('iklans'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('admin.iklan.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
{
    $request->validate([
        'judul' => 'required|max:255',
        'deskripsi' => 'nullable',
        'link' => 'nullable|url',
        'gambar' => 'required|image|mimes:jpg,jpeg,png|max:2048',
        'status' => 'required|boolean',
    ]);

    $gambar = $request->file('gambar')->store('iklan', 'public');

    Iklan::create([
        'judul' => $request->judul,
        'deskripsi' => $request->deskripsi,
        'link' => $request->link,
        'gambar' => $gambar,
        'status' => $request->status,
        'admin_id' => Auth::id(),
    ]);

    return redirect()
        ->route('admin.iklan.index')
        ->with('success', 'Iklan berhasil ditambahkan.');
}

    /**
     * Display the specified resource.
     */
    public function show(Iklan $iklan)
{
    return view('admin.iklan.show', compact('iklan'));
}

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Iklan $iklan)
{
    return view('admin.iklan.edit', compact('iklan'));
}

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Iklan $iklan)
{
    $data = $request->validate([
        'judul' => 'required|max:255',
        'deskripsi' => 'nullable',
        'link' => 'nullable|url',
        'gambar' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
        'status' => 'required|boolean',
    ]);

    if ($request->hasFile('gambar')) {

        if ($iklan->gambar && Storage::disk('public')->exists($iklan->gambar)) {
            Storage::disk('public')->delete($iklan->gambar);
        }

        $data['gambar'] = $request->file('gambar')
                                  ->store('iklan', 'public');
    }

    $iklan->update($data);

    return redirect()
        ->route('admin.iklan.index')
        ->with('success', 'Iklan berhasil diperbarui.');
}

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Iklan $iklan)
{
    if ($iklan->gambar && Storage::disk('public')->exists($iklan->gambar)) {

        Storage::disk('public')->delete($iklan->gambar);

    }

    $iklan->delete();

    return redirect()
        ->route('admin.iklan.index')
        ->with('success', 'Iklan berhasil dihapus.');
}
}