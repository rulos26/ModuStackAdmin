<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'ModuStackUser') }}</title>

    <!-- AdminLTE & dependencies via CDN -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@fortawesome/fontawesome-free@6.5.2/css/all.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/admin-lte@4.2/dist/css/adminlte.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/overlayscrollbars@2.6.1/styles/overlayscrollbars.min.css">

    @stack('styles')
</head>
<body class="hold-transition login-page bg-body-tertiary">
<div class="login-box">
    <div class="login-logo">
        <a href="{{ url('/') }}"><b>{{ config('app.name', 'ModuStackUser') }}</b></a>
    </div>
    <div class="card card-outline card-primary shadow">
        <div class="card-body">
            {{ $slot }}
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" defer></script>
<script src="https://cdn.jsdelivr.net/npm/overlayscrollbars@2.6.1/browser/overlayscrollbars.browser.es6.min.js" defer></script>
<script src="https://cdn.jsdelivr.net/npm/admin-lte@4.2/dist/js/adminlte.min.js" defer></script>

@stack('scripts')
</body>
</html>
