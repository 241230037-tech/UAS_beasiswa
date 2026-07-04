@extends('layouts.app')

@section('content')

<div class="card">

    <div class="card-header">
        <h3>Edit Pengguna</h3>
    </div>

    <form action="{{ route('admin.users.update', $user->id) }}" method="POST">

        @csrf
        @method('PUT')

        <div class="card-body">

            <div class="form-group">
                <label>Nama</label>
                <input
                    type="text"
                    name="name"
                    class="form-control"
                    value="{{ old('name', $user->name) }}"
                    required>
            </div>

            <div class="form-group">
                <label>Email</label>
                <input
                    type="email"
                    name="email"
                    class="form-control"
                    value="{{ old('email', $user->email) }}"
                    required>
            </div>

            <div class="form-group">
                <label>Password Baru</label>
                <input
                    type="password"
                    name="password"
                    class="form-control">

                <small class="text-muted">
                    Kosongkan jika tidak ingin mengubah password.
                </small>
            </div>

            <div class="form-group">
                <label>Role</label>

                <select name="role" class="form-control">

                    <option value="admin"
                        {{ $user->role == 'admin' ? 'selected' : '' }}>
                        Admin
                    </option>

                    <option value="mahasiswa"
                        {{ $user->role == 'mahasiswa' ? 'selected' : '' }}>
                        Mahasiswa
                    </option>

                </select>

            </div>

            <div class="form-group">

                <label>Status</label>

                <select name="status" class="form-control">

                    <option value="1"
                        {{ $user->status ? 'selected' : '' }}>
                        Aktif
                    </option>

                    <option value="0"
                        {{ !$user->status ? 'selected' : '' }}>
                        Nonaktif
                    </option>

                </select>

            </div>

        </div>

        <div class="card-footer">

            <button class="btn btn-success">

                <i class="fas fa-save"></i>

                Update

            </button>

            <a href="{{ route('admin.users.index') }}"
               class="btn btn-secondary">

                Kembali

            </a>

        </div>

    </form>

</div>

@endsection