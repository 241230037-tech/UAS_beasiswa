@extends('layouts.app')

@section('content')

<div class="card">

    <div class="card-header">

        <h3 class="card-title">
            Detail Beasiswa
        </h3>

        <div class="card-tools">

            <a href="{{ route('admin.beasiswa.index') }}"
               class="btn btn-secondary btn-sm">

                <i class="fas fa-arrow-left"></i>
                Kembali

            </a>

        </div>

    </div>

    <div class="card-body">

        <div class="row">

            <div class="col-md-4">

                @if($beasiswa->gambar)

                    <img src="{{ asset('storage/'.$beasiswa->gambar) }}"
                         class="img-fluid img-thumbnail">

                @else

                    <img src="https://via.placeholder.com/400x250?text=Tidak+Ada+Gambar"
                         class="img-fluid img-thumbnail">

                @endif

            </div>

            <div class="col-md-8">

                <table class="table table-bordered">

                    <tr>
                        <th width="220">Nama Beasiswa</th>
                        <td>{{ $beasiswa->nama }}</td>
                    </tr>

                    <tr>
                        <th>Penyelenggara</th>
                        <td>{{ $beasiswa->penyelenggara }}</td>
                    </tr>

                    <tr>
                        <th>Deskripsi</th>
                        <td>{{ $beasiswa->deskripsi }}</td>
                    </tr>

                    <tr>
                        <th>Persyaratan</th>
                        <td>{{ $beasiswa->persyaratan ?? '-' }}</td>
                    </tr>

                    <tr>
                        <th>Minimal IPK</th>
                        <td>{{ $beasiswa->minimal_ipk ?? '-' }}</td>
                    </tr>

                    <tr>
                        <th>Jurusan</th>
                        <td>{{ $beasiswa->jurusan ?? '-' }}</td>
                    </tr>

                    <tr>
                        <th>Semester Minimal</th>
                        <td>{{ $beasiswa->semester_min ?? '-' }}</td>
                    </tr>

                    <tr>
                        <th>Domisili</th>
                        <td>{{ $beasiswa->domisili ?? '-' }}</td>
                    </tr>

                    <tr>
                        <th>Deadline</th>
                        <td>{{ $beasiswa->deadline }}</td>
                    </tr>

                    <tr>
                        <th>Status</th>
                        <td>

                            @if($beasiswa->status == 'dibuka')

                                <span class="badge badge-success">
                                    Dibuka
                                </span>

                            @else

                                <span class="badge badge-danger">
                                    Ditutup
                                </span>

                            @endif

                        </td>
                    </tr>

                    <tr>
                        <th>Link Pendaftaran</th>
                        <td>

                            @if($beasiswa->link_pendaftaran)

                                <a href="{{ $beasiswa->link_pendaftaran }}"
                                   target="_blank">

                                    {{ $beasiswa->link_pendaftaran }}

                                </a>

                            @else

                                -

                            @endif

                        </td>
                    </tr>

                    <tr>
                        <th>Dibuat Oleh</th>
                        <td>

                            {{ $beasiswa->admin?->name ?? '-' }}

                        </td>
                    </tr>

                    <tr>
                        <th>Dibuat Pada</th>
                        <td>{{ $beasiswa->created_at }}</td>
                    </tr>

                    <tr>
                        <th>Terakhir Diubah</th>
                        <td>{{ $beasiswa->updated_at }}</td>
                    </tr>

                </table>

            </div>

        </div>

    </div>

</div>

@endsection