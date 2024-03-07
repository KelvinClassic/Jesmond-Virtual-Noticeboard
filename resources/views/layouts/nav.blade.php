<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    {{-- favicon --}}
    <link rel="icon" type="image/x-icon" href="{{ asset('images/favicon.ico') }}">

    {{-- include bootstrap css --}}
    <link rel="stylesheet" href="{{ asset('css/bootstrap.min.css') }}">

    {{-- include fontawesome css --}}
    <link rel="stylesheet" href="{{ asset('css/all.min.css') }}">

    {{-- include nav css --}}
    <link rel="stylesheet" href="{{ asset('css/pages/common/nav.css') }}">
    <link rel="stylesheet" href="{{ asset('css/pages/common/include.css') }}">

    @yield('css')

    {{-- include footer css --}}
    <link rel="stylesheet" href="{{ asset('css/pages/common/footer.css') }}">

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@100;200;300;400;500;600;700;800;900&family=Nunito:wght@200;300;400;500;600;700;800;900;1000&display=swap" rel="stylesheet">

    <title>@yield('title'):: Jesmond Library</title>
</head>

<body>
    {{-- open page width --}}
    <div class="page_width wrapper">
        <nav class="navbar navbar-expand-sm bg-white navbar-white sticky-top px-md-5">
            <div class="container-fluid">
                <a class="navbar-brand" href="{{ route('pages.index') }}">
                    <img src="{{ asset('images/Logo.jpg') }}" alt="Jesmond Logo" style="width: 100px" class="rounded-pill">
                </a>
                <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#collapsibleNavbar">
                    <span class="navbar-toggler-icon"></span>
                </button>
                <div class="collapse navbar-collapse" id="collapsibleNavbar">
                    <ul class="navbar-nav mx-auto">
                        <li class="nav-item">
                            <a class="nav-link text-uppercase" href="{{ route('pages.index') }}">Home</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link text-uppercase" href="{{ route('pages.events') }}">Event</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link text-uppercase" href="{{ route('poster.create') }}">Upload / Create Poster</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link text-uppercase" href="http://jesmondlibrary.org/about-us/" target="_blank">About us</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link text-uppercase" href="http://jesmondlibrary.org/contact-us/" target="_blank">Contact us</a>
                        </li>
                        @if (!Auth::check())
                        <li class="nav-item">
                            <a class="nav-link text-uppercase" href="{{ route('login') }}">Login</a>
                        </li>
                        @endif
                    </ul>
                    <ul class="navbar-nav">
                        <!-- Authentication Links -->
                        @auth
                        <li class="nav-item dropdown">
                            <a id="navbarDropdown" class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                <i style="border: 1px solid; border-radius: 999px; padding: 5px;" class="fa-solid fa-user"></i>
                            </a>

                            <ul class="dropdown-menu events text-center">
                                <li>
                                    <a class="dropdown-item" href="{{ route('pages.dashboard') }}">Dashboard</a>
                                </li>
                                <li>
                                    <a class="dropdown-item" href="{{ route('pages.account') }}">Account</a>
                                </li>
                                @if (Auth::user()->is_admin)
                                <li>
                                    <a class="dropdown-item" href="{{ route('admin.category') }}">Categories</a>
                                </li>
                                <li>
                                    <a class="dropdown-item {{ count($pendingEvents) ? 'text-danger' : '' }}" href="{{ route('admin.approval') }}">Approvals: {{ count($pendingEvents) }}</a>
                                </li>

                                @if (Auth::user()->is_super_admin)
                                <li>
                                    <a class="dropdown-item" href="{{ route('admin.createAdmin.view') }}">Manage Admins</a>
                                </li>

                                <li>
                                    <a class="dropdown-item" href="{{ route('admin.createAdmin.viewSuperAdmin') }}">Manage Super Admins</a>
                                </li>
                                @endif
                                @endif
                                <li>
                                    <a class="dropdown-item" href="{{ route('poster.create') }}">Create or Post Event`</a>
                                </li>
                                <li>
                                    <a class="dropdown-item" href="{{ route('pages.eventsPosted') }}">My Posted Event(s): {{ count($eventsPosted) }}</a>
                                </li>
                                <li>
                                    <a class="dropdown-item" href="{{ route('pages.bookmarks') }}">My Bookmarks</a>
                                </li>

                                <li>
                                    <a class="dropdown-item" href="{{ route('logout') }}" onclick="event.preventDefault();
                                                        document.getElementById('logout-form').submit();">
                                        {{ __('Logout') }}
                                    </a>

                                    <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                                        @csrf
                                    </form>
                                </li>
                            </ul>
                            <div class="dropdown-menu dropdown-menu-end" aria-labelledby="navbarDropdown">

                            </div>
                        </li>
                        @endauth
                    </ul>
                    <form class="search ms-auto" action="{{ route('pages.search') }}" method="GET">
                        <div class="search__wrapper">
                            <input type="text" name="search" placeholder="Enter keyword and press enter..." class="search__field">
                            <i type="submit" class="fa fa-search search__icon"></i>
                        </div>
                    </form>
                </div>
            </div>
        </nav>