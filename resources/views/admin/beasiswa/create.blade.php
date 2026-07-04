@extends('layouts.app')

@section('content')

<div class="card">

    <div class="card-header">
        <h3>Tambah Beasiswa</h3>
    </div>

<form action="{{ route('admin.beasiswa.store') }}"
      method="POST"
      enctype="multipart/form-data">
        @csrf

        <div class="card-body">

            <div class="form-group">
                <label>Nama Beasiswa</label>
                <input type="text" name="nama" class="form-control" required>
            </div>

            <div class="form-group">
                <label>Penyelenggara</label>
                <input type="text" name="penyelenggara" class="form-control" required>
            </div>

            <div class="form-group">
                <label>Deskripsi</label>
                <textarea name="deskripsi" class="form-control" rows="4"></textarea>
            </div>

            <div class="form-group">
                <label>Persyaratan</label>
                <textarea name="persyaratan" class="form-control" rows="4"></textarea>
            </div>

            <div class="row">

                <div class="col-md-3">
                    <label>Minimal IPK</label>
                    <input type="number" step="0.01" name="minimal_ipk" class="form-control">
                </div>

                <div class="col-md-3">
                    <label>Semester Minimal</label>
                    <input type="number" name="semester_min" class="form-control">
                </div>

                <div class="col-md-3">
                    <label>Jurusan</label>
                    <input type="text" name="jurusan" class="form-control">
                </div>

                <div class="col-md-3">
                    <label>Domisili</label>
                    <input type="text" name="domisili" class="form-control">
                </div>

            </div>

            <br>

            <div class="row">

                <div class="col-md-6">
                    <label>Deadline</label>
                    <input type="date" name="deadline" class="form-control">
                </div>

                <div class="col-md-6">
                    <label>Status</label>

                    <select name="status" class="form-control">

                        <option value="dibuka">Dibuka</option>

                        <option value="ditutup">Ditutup</option>

                    </select>

                </div>

            </div>

            <br>

            <div class="form-group">
                <label>Link Pendaftaran</label>
                <input type="url" name="link_pendaftaran" class="form-control">
            </div>

            <div class="form-group">

    <label>Gambar Beasiswa</label>

    <input type="file"
           name="gambar"
           class="form-control"
           accept="image/*">

</div>

        </div>

        <div class="card-footer">

            <button class="btn btn-primary">
                Simpan
            </button>

            <a href="{{ route('admin.beasiswa.index') }}"
               class="btn btn-secondary">

                Kembali

            </a>

        </div>

    </form>

</div>

@endsection