@extends('auth.layout')

@section('title', 'Login')

@section('content')
<div class="auth-main">
    <div class="auth-wrapper v3">
        <div class="auth-form">
            <div class="auth-header">
                <a href="#"><img src="{{ asset('backend/assets/images/logo-me.png') }}" alt="Logo" style="max-width: 160px; height: auto;"></a>
            </div>
            <form method="POST" action="{{ route('login') }}">
                @csrf
                <div class="card my-5">
                    <div class="card-body">
                        <div class="text-center mb-4">
                            <h3 class="fw-bold" style="font-family: 'Lato', sans-serif; color: #4A90E2;">Selamat datang di Toko SEHAT MEDIKA</h3>
                            <p class="font-roboto" style="font-size: 16px; color: #555;">Silahkan login untuk dapat mengakses fitur yang tersedia.</p>
                        </div>
                        <div class="form-group mb-3">
                            <label class="form-label" for="email">Email Address</label>
                            <input type="email" class="form-control @error('email') is-invalid @enderror" id="email" name="email" value="{{ old('email') }}" placeholder="Email Address" required>
                            @error('email')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>
                        <div class="form-group mb-3">
                            <label class="form-label" for="password">Password</label>
                            {{-- Menggunakan autocomplete="off" untuk password di halaman login --}}
                            <input type="password" class="form-control @error('password') is-invalid @enderror" id="password" name="password" placeholder="Password" required autocomplete="off">
                            @error('password')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>
                        <div class="d-flex mt-1 justify-content-between">
                            <div class="form-check">
                                <input class="form-check-input input-primary" type="checkbox" id="remember" name="remember" {{ old('remember') ? 'checked' : '' }}>
                                <label class="form-check-label text-muted" for="remember">Keep me sign in</label>
                            </div>
                            <a href="{{ route('password.request') }}" class="link-primary">Forgot Password?</a>
                        </div>
                        <div class="d-grid mt-4">
                            <button type="submit" class="btn btn-primary">Login</button>
                        </div>
                        <div class="text-center mt-3">
                            <a href="{{ route('register') }}" class="link-primary">Don't have an account? Sign up</a>
                        </div>
                    </div>
                </div>
            </form>
            <div class="auth-footer row">

            </div>
        </div>
    </div>
</div>
@endsection
