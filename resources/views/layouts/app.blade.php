<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="Free and 100% legal Discord bot and dashboard to facilitate the work of Protocol Officers (POs) in the Game of Thrones: Winter is Coming browser game.">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'PO Dashboard') }}</title>

    <!-- Scripts -->
    <script src="{{ asset('js/app.js') }}" defer></script>

    <!-- Favicon -->
    <link rel="apple-touch-icon" sizes="180x180" href="/apple-touch-icon.png">
    <link rel="icon" type="image/png" sizes="32x32" href="/favicon-32x32.png">
    <link rel="icon" type="image/png" sizes="16x16" href="/favicon-16x16.png">
    <link rel="manifest" href="/site.webmanifest">

    <!-- Fonts -->
    <link rel="dns-prefetch" href="//fonts.gstatic.com">
    <link href="https://fonts.googleapis.com/css?family=Nunito" rel="stylesheet">

    <!-- Styles -->
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">
    @yield('header')
</head>
<body>
    <div id="app">
        <nav class="navbar navbar-expand-md navbar-dark shadow-lg sticky-top navbar-bg-custom">
            <div class="container">
                <a class="navbar-brand" href="{{ url('/') }}">
                    <img src="/images/podblogo.png" width="177" height="60" alt=""/>
                </a>

                <span class="navbar-text">
                    Kingdoms In System: <span class="badge badge-info">{{ \App\Server::count() }}</span><br/>
                    Total PO Requests Served: <span class="badge badge-info">{{ \App\BuffRequest::count() }}</span>
                </span>

                @if(isset($server))
                    <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="{{ __('Toggle navigation') }}">
                        <span class="navbar-toggler-icon"></span>
                    </button>

                    <div class="collapse navbar-collapse" id="navbarSupportedContent">

                        <!-- Right Side Of Navbar -->
                        <ul class="navbar-nav ml-auto">

                            <!-- Authentication Links -->
                            <li class="nav-item dropdown">
                                <a id="navbarDropdown" class="nav-link dropdown-toggle" href="#" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" v-pre>
                                    {{ Auth::user()->name }} <span class="caret"></span>
                                </a>

                                <div class="dropdown-menu dropdown-menu-right" aria-labelledby="navbarDropdown">
                                    @if(\Illuminate\Support\Facades\Auth::user()->isAdminOfServer($server))
                                        <a class="dropdown-item" href="{{ route('admin.user.index') }}">{{ __('User Management') }}</a>
                                        <a class="dropdown-item" href="{{ route('admin.roles.index') }}">{{ __('Role Management') }}</a>
                                        <div class="dropdown-divider"></div>
                                        <a class="dropdown-item" href="{{ route('admin.webhooks.index') }}">{{ __('Webhook Management') }}</a>
                                        <div class="dropdown-divider"></div>
                                        <a class="dropdown-item" href="https://discord.gg/zr7wrQZeNX" target="_blank">Support & Feedback Discord</a>
                                        <div class="dropdown-divider"></div>
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
                        </ul>
                    </div>
                @endif
            </div>
        </nav>

        <main class="py-lg-4">
            @yield('content')
        </main>
    </div>
    <footer class="footer">
        <div class="container text-right">
            <form action="https://www.paypal.com/donate" method="post" target="_top">
                <input type="hidden" name="cmd" value="_donations" />
                <input type="hidden" name="business" value="J2WDLR4T3WBD8" />
                <input type="hidden" name="currency_code" value="USD" />
                <span class="text-muted">Created with <span style="color: #ae1c17;">&hearts;</span> by <b>D a n y</b>.</span>
                <input type="image" src="https://www.paypalobjects.com/en_US/i/btn/btn_donate_SM.gif" border="0" name="submit" title="PayPal - The safer, easier way to pay online!" alt="Donate with PayPal button" />
                <img alt="" border="0" src="https://www.paypal.com/en_US/i/scr/pixel.gif" width="1" height="1" />
            </form>
        </div>
    </footer>
    @yield('scripts')
</body>
</html>
