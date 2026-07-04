@extends('layouts.app')

@section('content')

<div class="card">

    <div class="card-header">

        <h3>Detail Pengguna</h3>

    </div>

    <div class="card-body">

        <table class="table table-bordered">

            <tr>
                <th width="200">Nama</th>
                <td>{{ $user->name }}</td>
            </tr>

            <tr>
                <th>Email</th>
                <td>{{ $user->email }}</td>
            </tr>

            <tr>
                <th>Role</th>

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

            </tr>

            <tr>
                <th>Status</th>

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

            </tr>

            <tr>
                <th>Dibuat</th>
                <td>{{ $user->created_at }}</td>
            </tr>

            <tr>
                <th>Terakhir Update</th>
                <td>{{ $user->updated_at }}</td>
            </tr>

        </table>

    </div>

    <div class="card-footer">

        <a href="{{ route('admin.users.index') }}"
           class="btn btn-secondary">

            Kembali

        </a>

    </div>

</div>

@endsection