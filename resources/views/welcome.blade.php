<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>Not Jira</title>

    <!-- Styles / Scripts -->
    @if (file_exists(public_path('build/manifest.json')) || file_exists(public_path('hot')))
    @vite(['resources/sass/app.scss','resources/css/app.css', 'resources/js/app.js'])
    @else
    @endif
</head>

<body class="nj-home-background z-n1">
    @if (Route::has('login'))
    <nav class="flex flex-1 justify-end">
        @auth
        <a href="{{ url('/dashboard') }}"
            class="text-decoration-none fs-5 px-3 py-2 text-white position-absolute top-0 end-0">
            Dashboard
        </a>
        @else
        <a href="{{ route('login') }}"
            class="fs-5 rounded-md px-3 py-2 text-white ring-1 ring-transparent transition hover:text-white/70 focus:outline-none focus-visible:ring-[#FF2D20]">
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