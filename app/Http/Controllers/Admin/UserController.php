<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    public function index()
{
    $users = User::latest()->paginate(10);

    return view('admin.users.index', compact('users'));
}

    /**
     * Show the form for creating a new resource.
     */
    public function create()
{
    return view('admin.users.create');
}

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
{
    $request->validate([
        'name' => 'required|max:255',
        'email' => 'required|email|unique:users,email',
        'password' => 'required|min:6',
        'role' => 'required|in:admin,mahasiswa',
        'status' => 'required|boolean',
    ]);

    User::create([
        'name' => $request->name,
        'email' => $request->email,
        'password' => Hash::make($request->password),
        'role' => $request->role,
        'status' => $request->status,
    ]);

    return redirect()
        ->route('admin.users.index')
        ->with('success', 'User berhasil ditambahkan.');
}

    /**
     * Display the specified resource.
     */
    public function show(User $user)
{
    return view('admin.users.show', compact('user'));
}

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(User $user)
{
    return view('admin.users.edit', compact('user'));
}

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, User $user)
{
    $data = $request->validate([
        'name' => 'required|max:255',
        'email' => 'required|email|unique:users,email,' . $user->id,
        'role' => 'required|in:admin,mahasiswa',
        'status' => 'required|boolean',
    ]);

    if ($request->filled('password')) {

        $request->validate([
            'password' => 'min:6',
        ]);

        $data['password'] = Hash::make($request->password);
    }

    $user->update($data);

    return redirect()
        ->route('admin.users.index')
        ->with('success', 'User berhasil diperbarui.');
}

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(User $user)
{
    $user->delete();

    return redirect()
        ->route('admin.users.index')
        ->with('success', 'User berhasil dihapus.');
}
}
