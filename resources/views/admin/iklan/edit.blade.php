@extends('layouts.app')

@section('content')

<div class="card">

    <div class="card-header">
        <h3>Edit Iklan</h3>
    </div>

    <form action="{{ route('admin.iklan.update', $iklan->id) }}"
          method="POST"
          enctype="multipart/form-data">

        @csrf
        @method('PUT')

        <div class="card-body">

            <div class="form-group">

                <label>Judul</label>

                <input type="text"
                       name="judul"
                       class="form-control"
                       value="{{ old('judul', $iklan->judul) }}"
                       required>

            </div>

            <div class="form-group">

                <label>Deskripsi</label>

                <textarea name="deskripsi"
                          rows="4"
                          class="form-control">{{ old('deskripsi', $iklan->deskripsi) }}</textarea>

            </div>

            <div class="form-group">

                <label>Link</label>

                <input type="url"
                       name="link"
                       class="form-control"
                       value="{{ old('link', $iklan->link) }}">

            </div>

            <div class="form-group">

                <label>Gambar Saat Ini</label>

                <br>

                @if($iklan->gambar)

                    <img src="{{ asset('storage/'.$iklan->gambar) }}"
                         class="img-thumbnail mb-3"
                         width="250">

                @else

                    <p class="text-muted">Belum ada gambar.</p>

                @endif

            </div>

            <div class="form-group">

                <label>Ganti Gambar</label>

                <input type="file"
                       name="gambar"
                       class="form-control">

                <small class="text-muted">
                    Kosongkan jika tidak ingin mengganti gambar.
                </small>

            </div>

            <div class="form-group">

                <label>Status</label>

                <select name="status" class="form-control">

                    <option value="1"
                        {{ old('status', $iklan->status) == 1 ? 'selected' : '' }}>
                        Aktif
                    </option>

                    <option value="0"
                        {{ old('status', $iklan->status) == 0 ? 'selected' : '' }}>
                        Nonaktif
                    </option>

                </select>

            </div>

        </div>

        <div class="card-footer">

            <button class="btn btn-success">

                <i class="fas fa-save"></i> Update

            </button>

            <a href="{{ route('admin.iklan.index') }}"
               class="btn btn-secondary">

                Kembali

            </a>

        </div>

    </form>

</div>

@endsection