@extends('layouts.app')

@section('content')

<div class="card">

    <div class="card-header">

        <h3>Tambah Pengguna</h3>

    </div>

    <form action="{{ route('admin.users.store') }}" method="POST">

        @csrf

        <div class="card-body">

            <div class="form-group">

                <label>Nama</label>

                <input type="text"
                       name="name"
                       class="form-control"
                       required>

            </div>

            <div class="form-group">

                <label>Email</label>

                <input type="email"
                       name="email"
                       class="form-control"
                       required>

            </div>

            <div class="form-group">

                <label>Password</label>

                <input type="password"
                       name="password"
                       class="form-control"
                       required>

            </div>

            <div class="form-group">

                <label>Role</label>

                <select name="role" class="form-control">

                    <option value="admin">Admin</option>

                    <option value="mahasiswa">Mahasiswa</option>

                </select>

            </div>

            <div class="form-group">

                <label>Status</label>

                <select name="status" class="form-control">

                    <option value="1">Aktif</option>

                    <option value="0">Nonaktif</option>

                </select>

            </div>

        </div>

        <div class="card-footer">

            <button class="btn btn-primary">

                Simpan

            </button>

            <a href="{{ route('admin.users.index') }}"
               class="btn btn-secondary">

                Kembali

            </a>

        </div>

    </form>

</div>

@endsection