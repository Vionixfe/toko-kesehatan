<?php

    namespace App\Providers;

    use Illuminate\Support\ServiceProvider;
    use Illuminate\Support\Facades\View;
    use Illuminate\Support\Facades\Auth;
    use App\Models\Cart;

    class CartServiceProvider extends ServiceProvider
    {
        /**
         * Register services.
         */
        public function register(): void
        {
            //
        }

        /**
         * Bootstrap services.
         */
        public function boot(): void
        {
            // Bagikan data ke semua view
            View::composer('*', function ($view) {
                $cartCount = 0;
                // Cek jika user sudah login
                if (Auth::check()) {
                    // Hitung jumlah item di keranjang milik user yang login
                    $cartCount = Cart::where('user_id', Auth::id())->count();
                }
                // Kirim variabel $cartCount ke semua view
                $view->with('cartCount', $cartCount);
            });
        }
    }
    