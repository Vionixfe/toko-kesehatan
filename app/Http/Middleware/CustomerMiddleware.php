<?php

    namespace App\Http\Middleware;

    use Closure;
    use Illuminate\Http\Request;
    use Illuminate\Support\Facades\Auth;
    use Symfony\Component\HttpFoundation\Response;

    class CustomerMiddleware
    {
        /**
         * Handle an incoming request.
         */
        public function handle(Request $request, Closure $next): Response
        {
            // Cek: Apakah pengguna sudah login DAN perannya adalah 'customer'?
            if (Auth::check() && Auth::user()->role === 'customer') {
                // Jika ya, izinkan dia melanjutkan aksinya (misal: menambah ke keranjang).
                return $next($request);
            }

            // Jika tidak (berarti dia admin atau terjadi anomali), tendang kembali ke halaman sebelumnya
            // dengan pesan error.
            return redirect()->back()->with('error', 'Aksi ini hanya untuk customer.');
        }
    }
    