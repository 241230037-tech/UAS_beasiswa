@extends('layouts.app')

@section('content')

<div class="card">

    <div class="card-header">

        <h3 class="card-title">
            Detail Iklan
        </h3>

    </div>

    <div class="card-body">

        <div class="row">

            <div class="col-md-4 text-center">

                @if($iklan->gambar)

                    <img src="{{ asset('storage/'.$iklan->gambar) }}"
                         class="img-fluid rounded shadow"
                         style="max-height:300px;">

                @else

                    <img src="https://via.placeholder.com/300x250?text=No+Image"
                         class="img-fluid rounded">

                @endif

            </div>

            <div class="col-md-8">

                <table class="table table-bordered">

                    <tr>
                        <th width="200">Judul</th>
                        <td>{{ $iklan->judul }}</td>
                    </tr>

                    <tr>
                        <th>Deskripsi</th>
                        <td>{{ $iklan->deskripsi }}</td>
                    </tr>

                    <tr>
                        <th>Link</th>
                        <td>

                            @if($iklan->link)

                                <a href="{{ $iklan->link }}"
                                   target="_blank">

                                    {{ $iklan->link }}

                                </a>

                            @else

                                -

                            @endif

                        </td>
                    </tr>

                    <tr>
                        <th>Status</th>

                        <td>

                            @if($iklan->status)

                                <span class="badge badge-success">
                                    Aktif
                                </span>

                            @else

                                <span class="badge badge-danger">
                                    Nonaktif
                                </span>

                            @endif

                        </td>

                    </tr>

                    <tr>
                        <th>Admin</th>

                        <td>

                            {{ $iklan->admin->name ?? '-' }}

                        </td>

                    </tr>

                    <tr>
                        <th>Dibuat</th>

                        <td>

                            {{ $iklan->created_at->format('d M Y H:i') }}

                        </td>

                    </tr>

                </table>

            </div>

        </div>

    </div>

    <div class="card-footer">

        <a href="{{ route('admin.iklan.index') }}"
           class="btn btn-secondary">

            Kembali

        </a>

    </div>

</div>

@endsection