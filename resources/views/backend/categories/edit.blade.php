@extends('backend.template.main') {{-- Pastikan path ini sesuai dengan layout Anda --}}

@section('title', 'Edit Kategori')

@section('content')
<div class="container-fluid">

    <!-- Page Heading -->
    <h1 class="h3 mb-4 text-gray-800">Edit Kategori</h1>

    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">Formulir Edit Kategori</h6>
        </div>
        <div class="card-body">
            {{-- Form action menunjuk ke route update dengan parameter category --}}
            <form action="{{ route('categories.update', $category->id) }}" method="POST">
                @csrf
                @method('PUT') {{-- Method spoofing untuk request UPDATE --}}

                <div class="mb-3">
                    <label for="name" class="form-label">Nama Kategori</label>
                    <input type="text" class="form-control @error('name') is-invalid @enderror" id="name" name="name" value="{{ old('name', $category->name) }}" required>
                    
                    {{-- Menampilkan pesan error validasi untuk field 'name' --}}
                    @error('name')
                        <div class="invalid-feedback">
                            {{ $message }}
                        </div>
                    @enderror
                </div>

                <hr>

                <div class="d-flex justify-content-end">
                    <a href="{{ route('categories.index') }}" class="btn btn-secondary me-2">
                        <i class="fas fa-arrow-left"></i> Kembali
                    </a>
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-save"></i> Simpan 
                    </button>
                </div>
            </form>
        </div>
    </div>

</div>
@endsection
