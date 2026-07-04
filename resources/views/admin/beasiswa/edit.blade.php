@extends('layouts.app')

@section('content')

<div class="card">

    <div class="card-header">
        <h3 class="card-title">Edit Beasiswa</h3>
    </div>

    <form action="{{ route('admin.beasiswa.update', $beasiswa->id) }}"
          method="POST"
          enctype="multipart/form-data">

        @csrf
        @method('PUT')

        <div class="card-body">

            @if ($errors->any())

                <div class="alert alert-danger">

                    <ul class="mb-0">

                        @foreach ($errors->all() as $error)

                            <li>{{ $error }}</li>

                        @endforeach

                    </ul>

                </div>

            @endif

            <div class="form-group">
                <label>Nama Beasiswa</label>
                <input
                    type="text"
                    name="nama"
                    class="form-control"
                    value="{{ old('nama', $beasiswa->nama) }}"
                    required>
            </div>

            <div class="form-group">
                <label>Penyelenggara</label>
                <input
                    type="text"
                    name="penyelenggara"
                    class="form-control"
                    value="{{ old('penyelenggara', $beasiswa->penyelenggara) }}"
                    required>
            </div>

            <div class="form-group">
                <label>Deskripsi</label>
                <textarea
                    name="deskripsi"
                    rows="4"
                    class="form-control"
                    required>{{ old('deskripsi', $beasiswa->deskripsi) }}</textarea>
            </div>

            <div class="form-group">
                <label>Persyaratan</label>
                <textarea
                    name="persyaratan"
                    rows="4"
                    class="form-control">{{ old('persyaratan', $beasiswa->persyaratan) }}</textarea>
            </div>

            <div class="row">

                <div class="col-md-3">
                    <label>Minimal IPK</label>
                    <input
                        type="number"
                        step="0.01"
                        name="minimal_ipk"
                        class="form-control"
                        value="{{ old('minimal_ipk', $beasiswa->minimal_ipk) }}">
                </div>

                <div class="col-md-3">
                    <label>Semester Minimal</label>
                    <input
                        type="number"
                        name="semester_min"
                        class="form-control"
                        value="{{ old('semester_min', $beasiswa->semester_min) }}">
                </div>

                <div class="col-md-3">
                    <label>Jurusan</label>
                    <input
                        type="text"
                        name="jurusan"
                        class="form-control"
                        value="{{ old('jurusan', $beasiswa->jurusan) }}">
                </div>

                <div class="col-md-3">
                    <label>Domisili</label>
                    <input
                        type="text"
                        name="domisili"
                        class="form-control"
                        value="{{ old('domisili', $beasiswa->domisili) }}">
                </div>

            </div>

            <br>

            <div class="row">

                <div class="col-md-6">
                    <label>Deadline</label>
                    <input
                        type="date"
                        name="deadline"
                        class="form-control"
                        value="{{ old('deadline', $beasiswa->deadline) }}"
                        required>
                </div>

                <div class="col-md-6">
                    <label>Status</label>

                    <select name="status" class="form-control">

                        <option value="dibuka"
                            {{ old('status', $beasiswa->status) == 'dibuka' ? 'selected' : '' }}>
                            Dibuka
                        </option>

                        <option value="ditutup"
                            {{ old('status', $beasiswa->status) == 'ditutup' ? 'selected' : '' }}>
                            Ditutup
                        </option>

                    </select>

                </div>

            </div>

            <br>

            <div class="form-group">
                <label>Link Pendaftaran</label>
                <input
                    type="url"
                    name="link_pendaftaran"
                    class="form-control"
                    value="{{ old('link_pendaftaran', $beasiswa->link_pendaftaran) }}">
            </div>

            <div class="form-group">

                <label>Gambar Saat Ini</label>

                <br>

                @if($beasiswa->gambar)

                    <img
                        src="{{ asset('storage/'.$beasiswa->gambar) }}"
                        width="200"
                        class="img-thumbnail mb-3">

                @else

                    <p class="text-muted">
                        Belum ada gambar.
                    </p>

                @endif

            </div>

            <div class="form-group">

                <label>Ganti Gambar</label>

                <input
                    type="file"
                    name="gambar"
                    class="form-control">

                <small class="text-muted">
                    Kosongkan jika tidak ingin mengganti gambar.
                </small>

            </div>

        </div>

        <div class="card-footer">

            <button type="submit" class="btn btn-success">
                <i class="fas fa-save"></i>
                Update
            </button>

            <a href="{{ route('admin.beasiswa.index') }}"
               class="btn btn-secondary">

                <i class="fas fa-arrow-left"></i>
                Kembali

            </a>

        </div>

    </form>

</div>

@endsection