@extends('backend.template.main')

@section('title', 'Edit Profil')

@section('content')
<div class="container py-5" style="max-width: 700px;">
    {{-- Notifikasi sukses/error --}}
    @if (session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
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

    <h2 class="mb-4 fw-bold">Edit Profil</h2>
    <form method="POST" action="{{ route('profiles.update') }}" class="mb-5">
        @csrf
        @method('PUT')
        <div class="mb-3">
            <label class="form-label fw-semibold">Nama Lengkap</label>
            <input type="text" class="form-control-plaintext border-bottom @error('nama') is-invalid @enderror" name="nama" value="{{ old('nama', $user->nama) }}" required>
            @error('nama') <div class="invalid-feedback">{{ $message }}</div> @enderror
        </div>
        <div class="mb-3">
            <label class="form-label fw-semibold">Email</label>
            <input type="email" class="form-control-plaintext border-bottom @error('email') is-invalid @enderror" name="email" value="{{ old('email', $user->email) }}" required>
            @error('email') <div class="invalid-feedback">{{ $message }}</div> @enderror
        </div>
        <div class="mb-3">
            <label class="form-label fw-semibold">Tanggal Lahir</label>
            <input type="date" class="form-control-plaintext border-bottom @error('tanggal_lahir') is-invalid @enderror" name="tanggal_lahir" value="{{ old('tanggal_lahir', $user->tanggal_lahir ? $user->tanggal_lahir->format('Y-m-d') : '') }}">
            @error('tanggal_lahir') <div class="invalid-feedback">{{ $message }}</div> @enderror
        </div>
        <div class="mb-3">
            <label class="form-label fw-semibold">Jenis Kelamin</label>
            <select class="form-select border-0 border-bottom @error('gender') is-invalid @enderror" name="gender">
                <option value="">Pilih Jenis Kelamin</option>
                <option value="Laki-laki" {{ old('gender', $user->gender) == 'Laki-laki' ? 'selected' : '' }}>Laki-laki</option>
                <option value="Perempuan" {{ old('gender', $user->gender) == 'Perempuan' ? 'selected' : '' }}>Perempuan</option>
            </select>
            @error('gender') <div class="invalid-feedback">{{ $message }}</div> @enderror
        </div>
        <div class="mb-3">
            <label class="form-label fw-semibold">Alamat</label>
            <textarea class="form-control-plaintext border-bottom @error('alamat') is-invalid @enderror" name="alamat" rows="2">{{ old('alamat', $user->alamat) }}</textarea>
            @error('alamat') <div class="invalid-feedback">{{ $message }}</div> @enderror
        </div>
        <button type="submit" class="btn btn-primary px-4 mt-2">Simpan Perubahan</button>
    </form>

    <h2 class="mb-4 fw-bold">Ganti Password</h2>
    <form method="POST" action="{{ route('profiles.password.update') }}" class="card shadow-sm border-0 p-4 mb-4">
        @csrf
        @method('PUT')
        <div class="mb-3">
            <label class="form-label fw-semibold">Password Lama</label>
            <div class="input-group">
                <input type="password" class="form-control @error('current_password') is-invalid @enderror" name="current_password" id="current_password" placeholder="Masukkan password lama" required>
                <button class="btn btn-outline-secondary" type="button" onclick="togglePassword('current_password', this)">
                    <i class="fa fa-eye"></i>
                </button>
                @error('current_password') <div class="invalid-feedback d-block">{{ $message }}</div> @enderror
            </div>
        </div>
        <div class="mb-3">
            <label class="form-label fw-semibold">Password Baru</label>
            <div class="input-group">
                <input type="password" class="form-control @error('new_password') is-invalid @enderror" name="new_password" id="new_password" placeholder="Masukkan password baru" required>
                <button class="btn btn-outline-secondary" type="button" onclick="togglePassword('new_password', this)">
                    <i class="fa fa-eye"></i>
                </button>
                @error('new_password') <div class="invalid-feedback d-block">{{ $message }}</div> @enderror
            </div>
        </div>
        <div class="mb-3">
            <label class="form-label fw-semibold">Konfirmasi Password Baru</label>
            <div class="input-group">
                <input type="password" class="form-control" name="new_password_confirmation" id="new_password_confirmation" placeholder="Ulangi password baru" required>
                <button class="btn btn-outline-secondary" type="button" onclick="togglePassword('new_password_confirmation', this)">
                    <i class="fa fa-eye"></i>
                </button>
            </div>
        </div>
        <button type="submit" class="btn btn-warning px-4 mt-2 w-100 fw-bold">Ganti Password</button>
    </form>
    <script>
        function togglePassword(fieldId, btn) {
            const input = document.getElementById(fieldId);
            const icon = btn.querySelector('i');
            if (input.type === "password") {
                input.type = "text";
                icon.classList.remove('fa-eye');
                icon.classList.add('fa-eye-slash');
            } else {
                input.type = "password";
                icon.classList.remove('fa-eye-slash');
                icon.classList.add('fa-eye');
            }
        }
    </script>
</div>
@endsection