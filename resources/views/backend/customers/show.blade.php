@extends('backend.template.main')
@section('title', 'Detail Customer: ' . $user->nama)

@section('content')
<div class="container-fluid">

    <!-- Page Heading -->
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">Detail Customer</h1>
        <a href="{{ route('customers.index') }}" class="btn btn-sm btn-secondary shadow-sm">
            <i class="fas fa-arrow-left fa-sm text-white-50"></i> Kembali 
        </a>
    </div>

    <!-- Kartu Detail Customer yang Disederhanakan -->
    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">Profil: {{ $user->nama }}</h6>
        </div>
        <div class="card-body">
            {{-- Menggunakan Definition List untuk tampilan yang rapi --}}
            <dl class="row">
                <dt class="col-sm-3">Nama Lengkap</dt>
                <dd class="col-sm-9">{{ $user->nama }}</dd>

                <dt class="col-sm-3">Email</dt>
                <dd class="col-sm-9">{{ $user->email }}</dd>

                <dt class="col-sm-3">Jenis Kelamin</dt>
                <dd class="col-sm-9">{{ ucfirst($user->gender) ?? 'Tidak diisi' }}</dd>

                <dt class="col-sm-3">Tanggal Lahir</dt>
                <dd class="col-sm-9">{{ $user->tanggal_lahir ? \Carbon\Carbon::parse($user->birth_date)->format('d F Y') : 'Tidak diisi' }}</dd>

                <dt class="col-sm-3">Bergabung Pada</dt>
                <dd class="col-sm-9">{{ $user->created_at }}</dd>

                <dt class="col-sm-3">Alamat</dt>
                <dd class="col-sm-9">{{ $user->alamat ?? 'Tidak diisi' }}</dd>

                <dt class="col-sm-3">Role</dt>
                <dd class="col-sm-9">
                       <span class="badge bg-info text-white">{{ $user->role ?? 'Tidak diisi' }}</span>
                </dd>

            </dl>
            
            <hr>
           
        </div>
    </div>
</div>
@endsection
