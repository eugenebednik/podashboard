<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'PO Dashboard') }}</title>

    <!-- Scripts -->
    <script src="{{ asset('js/app.js') }}" defer></script>

    <!-- Fonts -->
    <link rel="dns-prefetch" href="//fonts.gstatic.com">
    <link href="https://fonts.googleapis.com/css?family=Nunito" rel="stylesheet">

    <!-- Styles -->
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">
    @yield('header')
</head>
<body>
    <div id="app">
        <nav class="navbar navbar-expand-md navbar-light bg-white shadow-sm">
            <div class="container">
                <a class="navbar-brand" href="{{ url('/') }}">
                    {{ config('app.name', 'PO Dashboard') }}
                </a>
                <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="{{ __('Toggle navigation') }}">
                    <span class="navbar-toggler-icon"></span>
                </button>

                <div class="collapse navbar-collapse" id="navbarSupportedContent">
                    <!-- Left Side Of Navbar -->
                    <ul class="navbar-nav mr-auto">

                    </ul>

                    <!-- Right Side Of Navbar -->
                    <ul class="navbar-nav ml-auto">
                        <!-- Authentication Links -->
                        @guest
                            @if(request()->query('server_id'))
                            <li class="nav-item">
                                <a class="nav-link" href="{{ route('main', ['server_id' => request()->query('server_id')]) }}">{{ __('Login') }}</a>
                            </li>
                            @endif
                        @else
                            @if(isset($server))
                            <li class="nav-item dropdown">
                                <a id="navbarDropdown" class="nav-link dropdown-toggle" href="#" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" v-pre>
                                    {{ Auth::user()->name }} <span class="caret"></span>
                                </a>

                                <div class="dropdown-menu dropdown-menu-right" aria-labelledby="navbarDropdown">
                                    @if(\Illuminate\Support\Facades\Auth::user()->isAdminOfServer($server))
                                        <a class="dropdown-item" href="{{ route('admin.user.index') }}">{{ __('User Management') }}</a>
                                        <a class="dropdown-item" href="{{ route('admin.roles.index') }}">{{ __('Role Management') }}</a>
                                        <a class="dropdown-item" href="{{ route('admin.webhooks.index') }}">{{ __('Webhook Management') }}</a>
                                    @endif
                                    <a class="dropdown-item" href="{{ route('login.logout', ['server_id' => $server->snowflake]) }}"
                                       onclick="event.preventDefault();
                                                   document.getElementById('logout-form').submit();">
                                        {{ __('Logout') }}
                                    </a>

                                    <form id="logout-form" action="{{ route('login.logout', ['server_id' => $server->snowflake]) }}" method="GET" style="display: none;">
                                        @csrf
                                    </form>
                                </div>
                            </li>
                            @endif
                        @endguest
                    </ul>
                </div>
            </div>
        </nav>

        <main class="py-lg-4">
            @yield('content')
        </main>
    </div>
    <footer class="footer">
        <div class="container text-right">
            <span class="text-muted">Created by Daenelys.</span>
        </div>
    </footer>
    @yield('scripts')
</body>
</html>
