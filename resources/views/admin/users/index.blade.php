@extends('layouts.app')

@section('content')

<div class="card">

    <div class="card-header">

        <h3 class="card-title">
            Data Pengguna
        </h3>

        <div class="card-tools">

            <a href="{{ route('admin.users.create') }}"
               class="btn btn-primary btn-sm">

                <i class="fas fa-plus"></i>
                Tambah User

            </a>

        </div>

    </div>

    <div class="card-body">

        @if(session('success'))

            <div class="alert alert-success">

                {{ session('success') }}

            </div>

        @endif

        <table class="table table-bordered table-striped">

            <thead>

                <tr>

                    <th>No</th>
                    <th>Nama</th>
                    <th>Email</th>
                    <th>Role</th>
                    <th>Status</th>
                    <th width="220">Aksi</th>

                </tr>

            </thead>

            <tbody>

                @forelse($users as $user)

                <tr>

                    <td>{{ $loop->iteration }}</td>

                    <td>{{ $user->name }}</td>

                    <td>{{ $user->email }}</td>

                    <td>

                        @if($user->role=='admin')

                            <span class="badge badge-danger">
                                Admin
                            </span>

                        @else

                            <span class="badge badge-info">
                                Mahasiswa
                            </span>

                        @endif

                    </td>

                    <td>

                        @if($user->status)

                            <span class="badge badge-success">
                                Aktif
                            </span>

                        @else

                            <span class="badge badge-secondary">
                                Nonaktif
                            </span>

                        @endif

                    </td>

                    <td>

                        <a href="{{ route('admin.users.show',$user->id) }}"
                           class="btn btn-info btn-sm">

                            <i class="fas fa-eye"></i>

                        </a>

                        <a href="{{ route('admin.users.edit',$user->id) }}"
                           class="btn btn-warning btn-sm">

                            <i class="fas fa-edit"></i>

                        </a>

                        <form action="{{ route('admin.users.destroy',$user->id) }}"
                              method="POST"
                              style="display:inline">

                            @csrf
                            @method('DELETE')

                            <button
                                class="btn btn-danger btn-sm"
                                onclick="return confirm('Yakin ingin menghapus user ini?')">

                                <i class="fas fa-trash"></i>

                            </button>

                        </form>

                    </td>

                </tr>

                @empty

                <tr>

                    <td colspan="6" class="text-center">

                        Belum ada data.

                    </td>

                </tr>

                @endforelse

            </tbody>

        </table>

        <br>

        {{ $users->links() }}

    </div>

</div>

@endsection