@extends('layouts.app')

@section('content')

<div class="card">

    <div class="card-header">

        <h3 class="card-title">
            Data Beasiswa
        </h3>

        <div class="card-tools">
            <a href="{{ route('admin.beasiswa.create') }}"
               class="btn btn-primary btn-sm">
                <i class="fas fa-plus"></i>
                Tambah Beasiswa
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
                    <th>Gambar</th>
                    <th>Nama</th>
                    <th>Penyelenggara</th>
                    <th>Deadline</th>
                    <th>Status</th>
                    <th width="220">Aksi</th>
                </tr>

            </thead>

            <tbody>

                @forelse($beasiswas as $item)

                <tr>

                    <td>{{ $loop->iteration }}</td>

                    <td width="100">

                        @if($item->gambar)

                            <img src="{{ asset('storage/'.$item->gambar) }}"
                                 alt="{{ $item->nama }}"
                                 class="img-thumbnail"
                                 width="80">

                        @else

                            <span class="text-muted">
                                Tidak ada
                            </span>

                        @endif

                    </td>

                    <td>{{ $item->nama }}</td>

                    <td>{{ $item->penyelenggara }}</td>

                    <td>{{ $item->deadline }}</td>

                    <td>

                        @if($item->status == 'dibuka')

                            <span class="badge badge-success">
                                Dibuka
                            </span>

                        @else

                            <span class="badge badge-danger">
                                Ditutup
                            </span>

                        @endif

                    </td>

                    <td>

                        <a href="{{ route('admin.beasiswa.show', $item->id) }}"
                           class="btn btn-info btn-sm">

                            <i class="fas fa-eye"></i>

                        </a>

                        <a href="{{ route('admin.beasiswa.edit', $item->id) }}"
                           class="btn btn-warning btn-sm">

                            <i class="fas fa-edit"></i>

                        </a>

                        <form action="{{ route('admin.beasiswa.destroy', $item->id) }}"
                              method="POST"
                              style="display:inline;">

                            @csrf
                            @method('DELETE')

                            <button class="btn btn-danger btn-sm"
                                    onclick="return confirm('Hapus data ini?')">

                                <i class="fas fa-trash"></i>

                            </button>

                        </form>

                    </td>

                </tr>

                @empty

                <tr>

                    <td colspan="7" class="text-center">

                        Belum ada data.

                    </td>

                </tr>

                @endforelse

            </tbody>

        </table>

    </div>

</div>

@endsection