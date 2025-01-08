<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }}</title>

    <!-- Fonts -->
    <link rel="dns-prefetch" href="//fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=Nunito" rel="stylesheet">

    <!-- Scripts -->
    @vite(['resources/sass/app.scss', 'resources/js/app.js'])
</head>

<body style="background-color: rgb(247, 247, 245)">
    <div id="app">
        <nav class="navbar navbar-expand-md navbar-light nj-nav-bar shadow-sm">
            <div class="container">
                <a class="navbar-brand d-flex align-items-center" href={{ url('/home')}}>
                    <img src="https://flowbite.com/docs/images/logo.svg" class="p-2" alt="Flowbite Logo">
                    {{ config('app.name', 'Not Jira') }}
                </a>
                @auth
                <button class="navbar-toggler" type="button" data-bs-toggle="collapse"
                    data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent"
                    aria-expanded="false" aria-label="{{ __('Toggle navigation') }}">
                    <span class="navbar-toggler-icon"></span>
                </button>
                <div class="collapse navbar-collapse" id="navbarSupportedContent">
                    <!-- Left Side Of Navbar -->

                    <ul class="navbar-nav me-auto ms-5">
                        <li class="nav-item">
                            <a class="nav-link" href="{{ url('/home')}}">Home</a>
                        </li>
                        @if(Auth::user()->role == 'admin')
                        <li class="nav-item">
                            <a class="nav-link" href="{{route('workers.show')}}">Workers</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="{{route('customers.show')}}">Customers</a>
                        </li><li class="nav-item">
                            <a class="nav-link" href="{{route('users.add')}}">Add user</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="{{route("tickets.index")}}">All tickets</a>
                        </li>
                        @endif
                    </ul>

                    <!-- Right Side Of Navbar -->
                    <ul class="navbar-nav ms-auto">
                        <!-- Authentication Links -->

                        <li class="nav-item dropdown ms-5 ms-md-0">
                            <a id="navbarDropdown" class="bi bi-person-fill-gear h3" href="#" role="button"
                                data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false" v-pre>
                            </a>
                            <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="navbarDropdown">
                                <li>
                                    <h6 class="dropdown-header">{{ Auth::user()->name }}<br>{{
                                        Auth::user()->email}}
                                    </h6>
                                </li>
                                <li>
                                    <a class="dropdown-item" href="{{ route('profile.show') }}">{{ __('Profile') }}</a>
                                </li>
                                <li>
                                    <hr class="dropdown-divider">
                                </li>
                                <li>
                                    <a class="dropdown-item" href="{{ route('logout') }}" onclick="event.preventDefault();
                                                     document.getElementById('logout-form').submit();">
                                        {{ __('Logout') }}
                                    </a>
                                </li>
                                <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                                    @csrf
                                </form>
                            </ul>
                        </li>
                    </ul>
                </div>
                @endauth
            </div>
        </nav>

        <main class="py-4">
            @yield('content')
        </main>
    </div>
</body>

</html>