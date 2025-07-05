<?php

    namespace App\Http\Middleware;

    use Closure;
    use Illuminate\Http\Request;
    use Illuminate\Support\Facades\Auth;
    use Symfony\Component\HttpFoundation\Response;

    class AdminMiddleware
    {
        /**
         * Handle an incoming request.
         */
        public function handle(Request $request, Closure $next): Response
        {
            // Cek: Apakah pengguna sudah login DAN perannya adalah 'admin'?
            if (Auth::check() && Auth::user()->role === 'admin') {
                // Jika ya, izinkan akses ke halaman selanjutnya.
                return $next($request);
            }

            // Jika tidak, tendang pengguna ke halaman beranda.
            return redirect('/');
        }
    }
