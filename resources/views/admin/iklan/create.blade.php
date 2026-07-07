@extends('layouts.app')

@section('content')

<div class="card">

    <div class="card-header">
        <h3>Tambah Iklan</h3>
    </div>

    <form action="{{ route('admin.iklan.store') }}"
          method="POST"
          enctype="multipart/form-data">

        @csrf

        <div class="card-body">

            <div class="form-group">

                <label>Judul</label>

                <input type="text"
                       name="judul"
                       class="form-control"
                       required>

            </div>

            <div class="form-group">

                <label>Deskripsi</label>

                <textarea name="deskripsi"
                          rows="4"
                          class="form-control"></textarea>

            </div>

            <div class="form-group">

                <label>Link</label>

                <input type="url"
                       name="link"
                       class="form-control">

            </div>

            <div class="form-group">

                <label>Gambar Banner</label>

                <input type="file"
                       name="gambar"
                       class="form-control">

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

            <a href="{{ route('admin.iklan.index') }}"
               class="btn btn-secondary">

                Kembali

            </a>

        </div>

    </form>

</div>

@endsection