@extends('auth.layout') {{-- Menggunakan layout yang Anda tentukan --}}

@section('title', 'Register') {{-- Menetapkan judul halaman --}}

@section('content')
<div class="auth-main">
    <div class="auth-wrapper v3">
        <div class="auth-form">
            <div class="auth-header">
                <a href="#"><img src="{{ asset('backend/assets/images/logo-me.png') }}" alt="Logo" style="max-width: 160px; height: auto;"></a>
            </div>
            <div class="card my-5">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-end mb-4">
                        <h3 class="mb-0"><b>Sign Up</b></h3> 
                        <a href="{{ route('login') }}" class="link-primary">Already have an account? Login Here</a> {{-- Teks diubah ke Bahasa Indonesia --}}
                    </div>

                    {{-- Formulir Registrasi --}}
                    <form method="POST" action="{{ route('register') }}">
                        @csrf {{-- Token CSRF untuk keamanan --}}

                        {{-- Kolom Nama Lengkap (Satu kolom penuh untuk nama) --}}
                        <div class="row">
                            <div class="col-md-12"> {{-- Menggunakan col-md-12 agar nama tetap satu baris penuh --}}
                                <div class="form-group mb-3">
                                    <label class="form-label" for="nama">Nama Lengkap*</label>
                                    <input type="text" class="form-control @error('nama') is-invalid @enderror" id="nama" name="nama" value="{{ old('nama') }}" placeholder="Nama Lengkap Anda" required autocomplete="name" autofocus>
                                    @error('nama')
                                        <div class="invalid-feedback">
                                            {{ $message }}
                                        </div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        {{-- Kolom Email Address (Satu kolom penuh untuk email) --}}
                        <div class="row">
                            <div class="col-md-12"> {{-- Menggunakan col-md-12 agar email tetap satu baris penuh --}}
                                <div class="form-group mb-3">
                                    <label class="form-label" for="email">Alamat Email*</label>
                                    <input type="email" class="form-control @error('email') is-invalid @enderror" id="email" name="email" value="{{ old('email') }}" placeholder="Alamat Email Anda" required autocomplete="email">
                                    @error('email')
                                        <div class="invalid-feedback">
                                            {{ $message }}
                                        </div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        {{-- Kolom Password dan Konfirmasi Password (Dua kolom per baris) --}}
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group mb-3">
                                    <label class="form-label" for="password">Password*</label>
                                    <input type="password" class="form-control @error('password') is-invalid @enderror" id="password" name="password" placeholder="Password Anda" required autocomplete="new-password">
                                    @error('password')
                                        <div class="invalid-feedback">
                                            {{ $message }}
                                        </div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group mb-3">
                                    <label class="form-label" for="password-confirm">Konfirmasi Password*</label>
                                    <input type="password" class="form-control" id="password-confirm" name="password_confirmation" placeholder="Konfirmasi Password Anda" required autocomplete="new-password">
                                </div>
                            </div>
                        </div>

                        {{-- Kolom Tanggal Lahir dan Jenis Kelamin (Dua kolom per baris) --}}
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group mb-3">
                                    <label class="form-label" for="tanggal_lahir">Tanggal Lahir*</label>
                                    <input type="date" class="form-control @error('tanggal_lahir') is-invalid @enderror" id="tanggal_lahir" name="tanggal_lahir" value="{{ old('tanggal_lahir') }}" required>
                                    @error('tanggal_lahir')
                                        <div class="invalid-feedback">
                                            {{ $message }}
                                        </div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group mb-3">
                                    <label class="form-label" for="gender">Jenis Kelamin*</label>
                                    <select class="form-control @error('gender') is-invalid @enderror" id="gender" name="gender" required>
                                        <option value="">Pilih Jenis Kelamin</option>
                                        <option value="Laki-laki" {{ old('gender') == 'Laki-laki' ? 'selected' : '' }}>Laki-laki</option>
                                        <option value="Perempuan" {{ old('gender') == 'Perempuan' ? 'selected' : '' }}>Perempuan</option>
                                    </select>
                                    @error('gender')
                                        <div class="invalid-feedback">
                                            {{ $message }}
                                        </div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        {{-- Kolom Alamat Lengkap (Satu kolom penuh) --}}
                        <div class="row">
                            <div class="col-md-12"> {{-- Menggunakan col-md-12 agar alamat tetap satu baris penuh --}}
                                <div class="form-group mb-3">
                                    <label class="form-label" for="alamat">Alamat Lengkap*</label>
                                    <textarea class="form-control @error('alamat') is-invalid @enderror" id="alamat" name="alamat" rows="3" placeholder="Alamat lengkap Anda" required>{{ old('alamat') }}</textarea>
                                    @error('alamat')
                                        <div class="invalid-feedback">
                                            {{ $message }}
                                        </div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        {{-- Tombol Create Account --}}
                        <div class="d-grid mt-3">
                            <button type="submit" class="btn btn-primary">Daftar Sekarang</button>
                        </div>
                    </form>
                    {{-- Akhir Formulir Registrasi --}}

                </div>
            </div>
            <div class="auth-footer row">
                {{-- Anda bisa menambahkan konten footer di sini jika diperlukan --}}
            </div>
        </div>
    </div>
</div>
@endsection

