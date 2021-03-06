<!DOCTYPE html>
<html lang="{{ config('app.locale') }}">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }}</title>

    <!-- Styles -->
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">
    <link href="{{ asset('css/bootstrap.min.css') }}" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-select/1.12.2/css/bootstrap-select.min.css" rel="stylesheet">

    <!-- Scripts -->
    <script>
        window.Laravel = {!! json_encode([
            'csrfToken' => csrf_token(),
        ]) !!};
    </script>
</head>
<body>
    <div id="app">
        <nav class="navbar navbar-default navbar-static-top">
            <div class="container">
                <div class="navbar-header">

                    <!-- Collapsed Hamburger -->
                    <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#app-navbar-collapse">
                        <span class="sr-only">Toggle Navigation</span>
                        <span class="icon-bar"></span>
                        <span class="icon-bar"></span>
                        <span class="icon-bar"></span>
                    </button>

                    <!-- Branding Image -->
                    <a class="navbar-brand" href="{{ url('/') }}">
                        {{ config('app.name', 'Laravel') }}
                    </a>
                </div>

                <div class="collapse navbar-collapse " id="app-navbar-collapse">
                    <!-- Left Side Of Navbar -->
                    <ul class="nav navbar-nav">
                        @if (Auth::user())
                            @if (Auth::user()->role == 'admin')
                                <li><a href="{{ route('users') }}">Utilizadores</a></li>
                                <li><a href="{{ route('materials') }}">Materiais</a></li>
                            @elseif (Auth::user()->role == 'healthcarepro')
                                <li class="dropdown">
                                    <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-expanded="false">
                                        Recursos <span class="caret"></span>
                                    </a>
                                    <ul class="dropdown-menu resourses" role="menu">
                                        <li><a href="{{ route('users') }}">Cuidadores</a></li>
                                        <li><a href="{{ route('patients') }}">Utentes</a></li>
                                        <li><a href="{{ route('needs') }}">Necessidades</a></li>
                                        <li><a href="{{ route('materials') }}">Materiais</a></li>
                                        <li><a href="{{ route('quizs') }}">Questionários</a></li>
                                        <li><a href="{{ route('questions') }}">Questões</a></li>
                                    </ul>
                                </li>
                                <li><a href="{{ route('users.notifications', ['user' => Auth::user()->id]) }}">Notificações 
                                        @if ($countNewNotifications > 0)
                                            <span style="background-color:#d9534f" class="badge badge-pill´">{{ $countNewNotifications }}</span>
                                        @else
                                            <span class="badge badge-pill´">{{ $countNewNotifications }}</span>
                                        @endif
                                    </a>
                                </li>
                            @endif
                        @endif
                    </ul>

                    <!-- Right Side Of Navbar -->
                    <ul class="nav navbar-nav navbar-right">
                        <!-- Authentication Links -->
                        @if (Auth::guest())
                            <li><a href="{{ route('login') }}">Login</a></li>
                        @else
                            <li class="dropdown">
                                <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-expanded="false">
                                    {{ Auth::user()->name }} <span class="caret"></span>
                                </a>

                                <ul class="dropdown-menu" role="menu">
                                    <li><a href="{{ route('profile', ['user' => Auth::user()->id]) }}">Perfil</a></li>
                                    <li>
                                        <a href="{{ route('logout') }}"
                                            onclick="event.preventDefault();
                                                     document.getElementById('logout-form').submit();">
                                            Logout
                                        </a>

                                        <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                                            {{ csrf_field() }}
                                        </form>
                                    </li>
                                </ul>
                            </li>
                        @endif
                    </ul>
                </div>
            </div>
        </nav>

        @yield('content')   
    
        <br /><br />

        <footer class="footer">
            <br>
            <div class="container">
                <div class="row">
                    <div class="col-lg-2 col-md-2"></div>
                    <div class="col-lg-8 col-md-8">
                        <p>Copyright @ IPL-ESTG-DEI - João Caroço, Nuno Gomes, Teddy Simões e Tiago Estácio</p>
                    </div>
                    <div class="col-lg-2 col-md-2"></div>
                </div>
                <br>
            </div>
        </footer>
    </div>

    <!-- Scripts -->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.0/jquery.min.js"></script>
    <script src="{{ asset('js/bootstrap.min.js') }}"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-select/1.12.2/js/bootstrap-select.min.js"></script>
    @yield('custom_js') 
</body>
</html>