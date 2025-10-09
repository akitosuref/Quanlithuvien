<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>Quản Lý Thư Viện</title>

    <!-- Fonts -->
    <link rel="dns-prefetch" href="//fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=Nunito" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css"
        integrity="sha512-iecdLmaskl7CVkqkXNQ/ZH/XLlvWZOJyj7Yy7tcenmpD1ypASozpmT/E0iPtmFIB46ZmdtAc9eNBvH0H/ZpiBw=="
        crossorigin="anonymous" referrerpolicy="no-referrer" />

    <!-- Scripts -->
    @vite(['resources/sass/app.scss', 'resources/js/app.js'])
    <link rel="stylesheet" href="{{ asset('css/style.css') }}">
</head>

<body>
    <div id="app" class="wrapper">
        @auth
            <nav id="sidebar">
                <div class="sidebar-header">
                    <h3>Thư Viện</h3>
                </div>
                <ul class="list-unstyled components">
                    <li>
                        <a href="{{ route('home') }}"><i class="fas fa-home"></i> Trang Chủ</a>
                    </li>
                    <li>
                        <a href="{{ route('books.index') }}"><i class="fas fa-book"></i> Quản Lý Sách</a>
                    </li>
                    <li>
                        <a href="{{ route('authors.index') }}"><i class="fas fa-user-edit"></i> Quản Lý Tác Giả</a>
                    </li>
                    <li>
                        <a href="{{ route('members.index') }}"><i class="fas fa-users"></i> Quản Lý Độc Giả</a>
                    </li>
                    <li>
                        <a href="{{ route('phieumuon.index') }}"><i class="fas fa-ticket-alt"></i> Quản Lý Mượn Trả</a>
                    </li>
                    <li>
                        <a href="{{ route('posts.index') }}"><i class="fas fa-newspaper"></i> Cảm nhận Sách</a>
                    </li>
                </ul>
            </nav>
        @endauth

        <div id="content" @guest style="margin-left:0" @endguest>
            @guest
                <nav class="navbar navbar-expand-lg navbar-light navbar-custom fixed-top" style="padding-left: 0;">
                    <div class="container-fluid">
                        <div class="collapse navbar-collapse">
                            <ul class="navbar-nav ms-auto">
                                @if (Route::has('login'))
                                    <li class="nav-item">
                                        <a class="nav-link" href="{{ route('login') }}">{{ __('Login') }}</a>
                                    </li>
                                @endif
                                @if (Route::has('register'))
                                    <li class="nav-item">
                                        <a class="nav-link" href="{{ route('register') }}">{{ __('Register') }}</a>
                                    </li>
                                @endif
                            </ul>
                        </div>
                    </div>
                </nav>
                <main class="main-content" style="margin-top: 60px;">
                    @yield('content')
                </main>
            @else
                <nav class="navbar navbar-expand-lg navbar-light navbar-custom fixed-top" style="padding-left: 250px;">
                    <div class="container-fluid">
                        <div class="collapse navbar-collapse">
                            <ul class="navbar-nav ms-auto">
                                <li class="nav-item dropdown">
                                    <a id="navbarDropdown" class="nav-link dropdown-toggle" href="#" role="button"
                                        data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false" v-pre>
                                        <i class="fas fa-user-circle"></i> {{ Auth::user()->name }}
                                    </a>
                                    <div class="dropdown-menu dropdown-menu-end" aria-labelledby="navbarDropdown">
                                        <a class="dropdown-item" href="{{ route('logout') }}"
                                            onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                                            <i class="fas fa-sign-out-alt"></i> {{ __('Logout') }}
                                        </a>
                                        <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                                            @csrf
                                        </form>
                                    </div>
                                </li>
                            </ul>
                        </div>
                    </div>
                </nav>
                <main class="main-content" style="margin-top: 60px;">
                    @yield('content')
                </main>
            @endguest
        </div>
    </div>
</body>

</html>