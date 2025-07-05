@extends('backend.template.main')

@section('title', 'Tambah Kategori')

@section('content')
<div class="container-fluid">
    {{-- Judul Halaman --}}
    <h1 class="h3 mb-4 text-gray-800">Tambah Kategori Baru</h1>
    
    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">Formulir Kategori</h6>
        </div>
        <div class="card-body">
            {{-- Form akan dikirim ke route 'admin.categories.store' dengan metode POST --}}
            <form method="POST" action="{{ route('categories.store') }}">
                
                {{-- @csrf adalah token keamanan wajib dari Laravel --}}
                @csrf

                <div class="form-group">
                    <label for="name">Nama Kategori</label>
                    
                    {{-- Input untuk nama kategori --}}
                    {{-- `old('name')` akan menjaga input sebelumnya jika validasi gagal --}}
                    {{-- `@error('name')` akan menambahkan class 'is-invalid' jika ada error validasi untuk 'name' --}}
                    <input type="text" class="form-control @error('name') is-invalid @enderror" id="name" name="name" value="{{ old('name') }}" required>
                    
                    {{-- Blok ini akan menampilkan pesan error jika validasi gagal --}}
                    @error('name')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                {{-- Tombol untuk menyimpan data --}}
                <button type="submit" class="btn btn-primary">Simpan</button>
                
                {{-- Tombol untuk kembali ke halaman daftar kategori --}}
                <a href="{{ route('categories.index') }}" class="btn btn-secondary">Batal</a>
            </form>
        </div>
    </div>
</div>
@endsection