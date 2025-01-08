<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>Not Jira</title>

    <!-- Styles / Scripts -->
    @if (file_exists(public_path('build/manifest.json')) || file_exists(public_path('hot')))
    @vite(['resources/sass/app.scss','resources/css/app.css', 'resources/js/app.js'])
    @endif
</head>

<body class="nj-home-background z-n1">
    @if (Route::has('login'))
    <nav class="flex flex-1 justify-end">
        @auth
        <a href="{{ route('home') }}"
            class="text-decoration-none fs-5 px-3 py-2 text-white position-absolute top-0 end-0">
            Dashboard
        </a>
        @else
        <a href="{{ route('login') }}"
            class="text-decoration-none fs-5 px-3 py-2 text-white position-absolute top-0 end-0">
            Log in
        </a>
        @endauth
    </nav>
    @endif
    <div class="nj-home-logo">
        <img src="https://flowbite.com/docs/images/logo.svg" class="p-2" alt="Flowbite Logo">
        {{ config('app.name', 'Not Jira') }}
    </div>
</body>

</html>