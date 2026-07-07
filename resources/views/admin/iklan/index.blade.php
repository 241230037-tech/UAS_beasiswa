@extends('layouts.app')

@section('content')

<div class="card">

    <div class="card-header">

        <h3 class="card-title">
            Data Iklan
        </h3>

        <div class="card-tools">
            <a href="{{ route('admin.iklan.create') }}"
               class="btn btn-primary btn-sm">
                <i class="fas fa-plus"></i>
                Tambah Iklan
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
                    <th>Judul</th>
                    <th>Link</th>
                    <th>Status</th>
                    <th width="220">Aksi</th>
                </tr>
            </thead>

            <tbody>

            @forelse($iklans as $item)

                <tr>

                    <td>{{ $loop->iteration }}</td>

                    <td width="120">

                        @if($item->gambar)

                            <img src="{{ asset('storage/'.$item->gambar) }}"
                                 width="100"
                                 class="img-thumbnail">

                        @else

                            <span class="text-muted">Tidak ada</span>

                        @endif

                    </td>

                    <td>{{ $item->judul }}</td>

                    <td>

                        @if($item->link)
                            <a href="{{ $item->link }}" target="_blank">
                                Lihat Link
                            </a>
                        @endif

                    </td>

                    <td>

                        @if($item->status)

    <span class="badge badge-success">
        Aktif
    </span>

@else

    <span class="badge badge-danger">
        Nonaktif
    </span>

@endif

                    </td>

                    <td>

                        <a href="{{ route('admin.iklan.show',$item->id) }}"
                           class="btn btn-info btn-sm">
                            <i class="fas fa-eye"></i>
                        </a>

                        <a href="{{ route('admin.iklan.edit',$item->id) }}"
                           class="btn btn-warning btn-sm">
                            <i class="fas fa-edit"></i>
                        </a>

                        <form action="{{ route('admin.iklan.destroy', $item->id) }}"
      method="POST"
      style="display:inline;">

    @csrf
    @method('DELETE')

    <button class="btn btn-danger btn-sm"
            onclick="return confirm('Yakin ingin menghapus iklan ini?')">

        <i class="fas fa-trash"></i>

    </button>

</form>

                    </td>

                </tr>

            @empty

                <tr>

                    <td colspan="6" class="text-center">

                        Belum ada data iklan.

                    </td>

                </tr>

            @endforelse

            </tbody>

        </table>

    </div>

</div>

@endsection