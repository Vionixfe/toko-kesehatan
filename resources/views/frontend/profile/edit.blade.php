@extends('layouts.frontend') 

@section('title', 'Edit Profil Saya')

@section('content')
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-lg-8">
            
            <h2 class="mb-4 fw-bold">Pengaturan Akun</h2>

            {{-- Notifikasi Sukses atau Error --}}
            @if (session('success'))
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    {{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif
            @if ($errors->any())
                <div class="alert alert-danger">
                    <ul class="mb-0">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <!-- Form Informasi Profil -->
            <div class="card shadow-sm mb-5">
                <div class="card-header">
                    <h5 class="mb-0">Informasi Profil</h5>
                </div>
                <div class="card-body p-4">
                    <form method="POST" action="{{ route('profile.update') }}">
                        @csrf
                        @method('PUT')
                     
                        <div class="mb-3">
                            <label class="form-label fw-semibold">Nama Lengkap</label>
                            <input type="text" class="form-control @error('nama') is-invalid @enderror" name="nama" value="{{ old('nama', $user->nama) }}" required>
                            @error('nama') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>
                        <div class="mb-3">
                            <label class="form-label fw-semibold">Email</label>
                            <input type="email" class="form-control @error('email') is-invalid @enderror" name="email" value="{{ old('email', $user->email) }}" required>
                            @error('email') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>
                        <div class="mb-3">
                            <label class="form-label fw-semibold">Tanggal Lahir</label>
                            <input type="date" class="form-control @error('tanggal_lahir') is-invalid @enderror" name="tanggal_lahir" value="{{ old('tanggal_lahir', $user->tanggal_lahir ? \Carbon\Carbon::parse($user->tanggal_lahir)->format('Y-m-d') : '') }}">
                            @error('tanggal_lahir') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>
                        <div class="mb-3">
                            <label class="form-label fw-semibold">Jenis Kelamin</label>
                            <select class="form-select @error('gender') is-invalid @enderror" name="gender">
                                <option value="">Pilih Jenis Kelamin</option>
                                <option value="Laki-laki" {{ old('gender', $user->gender) == 'Laki-laki' ? 'selected' : '' }}>Laki-laki</option>
                                <option value="Perempuan" {{ old('gender', $user->gender) == 'Perempuan' ? 'selected' : '' }}>Perempuan</option>
                            </select>
                            @error('gender') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>
                        <div class="mb-3">
                            <label class="form-label fw-semibold">Alamat</label>
                            <textarea class="form-control @error('alamat') is-invalid @enderror" name="alamat" rows="3">{{ old('alamat', $user->alamat) }}</textarea>
                            @error('alamat') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>
                        <button type="submit" class="btn btn-primary px-4 mt-2">Simpan Perubahan Profil</button>
                    </form>
                </div>
            </div>


            <!-- Form Ganti Password -->
            <div class="card shadow-sm">
                 <div class="card-header">
                    <h5 class="mb-0">Ganti Password</h5>
                </div>
                <div class="card-body p-4">
                    <form method="POST" action="{{ route('profile.password.update') }}">
                        @csrf
                        @method('PUT')
                        <div class="mb-3">
                            <label class="form-label fw-semibold">Password Lama</label>
                            <input type="password" class="form-control @error('current_password', 'updatePassword') is-invalid @enderror" name="current_password" required>
                            @error('current_password', 'updatePassword') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>
                        <div class="mb-3">
                            <label class="form-label fw-semibold">Password Baru</label>
                            <input type="password" class="form-control @error('new_password', 'updatePassword') is-invalid @enderror" name="new_password" required>
                            @error('new_password', 'updatePassword') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>
                        <div class="mb-3">
                            <label class="form-label fw-semibold">Konfirmasi Password Baru</label>
                            <input type="password" class="form-control" name="new_password_confirmation" required>
                        </div>
                        <button type="submit" class="btn btn-warning px-4 mt-2">Ganti Password</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
