<!DOCTYPE html>
<html lang="en">

<head>

    <title>Sehat Medika - @yield('title', 'Dashboard')</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=0, minimal-ui">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="description"
        content="Mantis is made using Bootstrap 5 design framework. Download the free admin template & use it for your project.">
    <meta name="keywords"
        content="Mantis, Dashboard UI Kit, Bootstrap 5, Admin Template, Admin Dashboard, CRM, CMS, Bootstrap Admin Template">
    <meta name="author" content="CodedThemes">

     {{-- CSRF Token untuk AJAX requests --}}
    <meta name="csrf-token" content="{{ csrf_token() }}">


    <link rel="icon" href="{{ asset('backend/assets/images/logo.png') }}" type="image/x-icon">

    <link rel="stylesheet"
        href="https://fonts.googleapis.com/css2?family=Public+Sans:wght@300;400;500;600;700&display=swap"
        id="main-font-link">

    <link rel="stylesheet" href="{{ asset('backend/assets/fonts/tabler-icons.min.css') }}">

    <link rel="stylesheet" href="{{ asset('backend/assets/fonts/feather.css') }}">

    <link rel="stylesheet" href="{{ asset('backend/assets/fonts/fontawesome.css') }}">

    <link rel="stylesheet" href="{{ asset('backend/assets/fonts/material.css') }}">

    <link rel="stylesheet" href="{{ asset('backend/assets/css/style.css') }}" id="main-style-link">

    <link rel="stylesheet" href="{{ asset('backend/assets/css/style-preset.css') }}">

    <style>
        .logo-lg {
            max-height: 40px;
            height: auto;
            width: auto;
        }
    </style>

    @stack('css')
</head>

<body data-pc-preset="preset-1" data-pc-direction="ltr" data-pc-theme="light">
    <div class="loader-bg">
        <div class="loader-track">
            <div class="loader-fill"></div>
        </div>
    </div>

    {{-- Sidebar --}}
    @include('backend.template.sidebar')

    {{-- Navbar --}}

    @include('backend.template.navbar')
    <div class="pc-container">
        <div class="pc-content">
           
            {{-- Ini adalah tempat di mana konten spesifik halaman akan dimasukkan --}}
            @yield('content')

        </div>
    </div>
    <footer class="pc-footer">
        <div class="footer-wrapper container-fluid">

        </div>
    </footer>

    {{-- PATH PERBAIKAN: apexcharts.min.js --}}
    <script src="{{ asset('backend/assets/js/plugins/apexcharts.min.js') }}"></script>
    {{-- PATH PERBAIKAN: dashboard-default.js --}}
    <script src="{{ asset('backend/assets/js/pages/dashboard-default.js') }}"></script>
    {{-- PATH PERBAIKAN: popper.min.js --}}
    <script src="{{ asset('backend/assets/js/plugins/popper.min.js') }}"></script>
    {{-- PATH PERBAIKAN: simplebar.min.js --}}
    <script src="{{ asset('backend/assets/js/plugins/simplebar.min.js') }}"></script>
    {{-- PATH PERBAIKAN: bootstrap.min.js --}}
    <script src="{{ asset('backend/assets/js/plugins/bootstrap.min.js') }}"></script>
    {{-- PATH PERBAIKAN: custom-font.js --}}
    <script src="{{ asset('backend/assets/js/fonts/custom-font.js') }}"></script>
    {{-- PATH PERBAIKAN: pcoded.js --}}
    <script src="{{ asset('backend/assets/js/pcoded.js') }}"></script>
    {{-- PATH PERBAIKAN: feather.min.js --}}
    <script src="{{ asset('backend/assets/js/plugins/feather.min.js') }}"></script>

    {{-- Script untuk inisialisasi Feather Icons --}}
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            if (typeof feather !== 'undefined') {
                feather.replace();
            }
        });
    </script>

    {{-- Skrip kustom dari template --}}
    <script>
        layout_change('light');
    </script>
    <script>
        change_box_container('false');
    </script>
    <script>
        layout_rtl_change('false');
    </script>
    <script>
        preset_change("preset-1");
    </script>
    <script>
        font_change("Public-Sans");
    </script>

    @stack('js')
</body>

</html>
