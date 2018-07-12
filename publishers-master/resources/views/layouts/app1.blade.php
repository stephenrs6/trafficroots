<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="_globalsign-domain-verification" content="qbw9lV17xS49YNux6uCCiE45peUkwMOWEjK5xjrONE" />
    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'TrafficRoots') }} @yield('title', '')</title>

    <!-- Scripts -->
    <script>
    window.Laravel = {
        'csrfToken': "{{ csrf_token() }}",
    }
    </script>
    <!-- Global and page level js -->
    @include('_styles') 
    @include('_scripts')
</head>

<body class="no-skin-config">
    <div id="wrapper">
        <nav class="navbar-default navbar-static-side" role="navigation">
            <div class="sidebar-collapse">
                <ul class="nav metismenu" id="side-menu">
                    <li class="nav-header">
                        <div class="dropdown profile-element">
                            <!-- Branding Image -->
                            <div class="text-center">
                                <a href="{{ url('/') }}">
                                        <img src="{{ url('/img/white_tree_logo.png') }}" alt="TrafficRoots" width="100" height="104">
                                </a>
                            </div>
                    </li>
                    <!-- Authentication Links -->
                    @if (Auth::guest())
                    <li id="nav_login" class="active nav-click">
                        <a href="{{ url('/login') }}">
                            <i class="fa fa-sign-in fa-2x"></i>
                            <span class="nav-label">Login</span>
                        </a>
                    </li>
                    <li id="nav_register" class="nav-click">
                        <a href="{{ url('/register') }}">
                            <i class="fa fa-pencil fa-2x"></i>
                            <span class="nav-label">Register</span>
                        </a>
                    </li>
                    {{--
                    <li id="nav_about" class="nav-click">
                        <a href="{{ url('/about') }}">
                            <i class="fa fa-group fa-2x"></i>
                            <span class="nav-label">About</span>
                        </a>
                    </li> --}} @else
                    <li id="nav_pub" class="nav-click">
                        <a href="{{ url('/home') }}">
                            <i class="fa fa-desktop fa-2x"></i>
                            <span class="nav-label">Publisher</span>
                            <span class="fa arrow"></span>
                        </a>
                        <ul class="nav nav-second-level collapse">
                            <li>
                                <a href="{{ URL::to('/home?type=1') }}">Dashboard</a>
                            </li>
                            <li>
                                <a href="{{ URL::to('home?type=1') }}">Dashboard</a>
                            </li>
                            <li>
                                <a href="{{ URL::to('sites') }}">Sites</a>
                            </li>
                            <li>
                                <a href="{{ URL::to('stats/pub') }}">Stats</a>
                            </li>
                            <li>
                                <a href="{{URL::to('account/publisher')}}">Account</a>
                            </li> 
                            <li>
                                <a href="{{URL::to('faq_publisher')}}">FAQ</a>
                            </li>                            
                        </ul>
                    </li>
                    <li id="nav_buyer" class="nav-click">
                        <a href="{{ url('/buyers') }}">
                            <i class="fa fa-bullhorn fa-2x"></i>
                            <span class="nav-label">Advertiser</span>
                            <span class="fa arrow"></span>
                        </a>
                        <ul class="nav nav-second-level collapse">
                            <li>
                                <a href="{{URL::to('home?type=2')}}">Dashboard</a>
                            </li>
                            <li>
                                <a href="{{URL::to('campaigns')}}">Campaigns</a>
                            </li>
                            <li>
                                <a href="{{URL::to('buyers/media')}}">Media</a>
                            </li>  
                            <li>
                                <a href="{{URL::to('buyers/links')}}">Links</a>
                            </li>
                            <li>
                                <a href="{{URL::to('buyers/folders')}}">Folders</a>
                            </li>                          
                            <li>
                                <a href="{{URL::to('buyers/account')}}">Account</a>
                            </li>                            
                            <li>
                                <a href="{{URL::to('faq_advertiser')}}">FAQ</a>
                            </li>
                        </ul>
                    </li>
                    <li id="nav_support" class="nav-click">
                        <a href="{{ url('/profile') }}">
                            <i class="fa fa-address-book-o fa-2x"></i>
                            <span class="nav-label">Profile</span>
                        </a>
                    </li>
                    <li id="nav_support" class="nav-click">
                        <a href="{{ url('/tickets') }}">
                            <i class="fa fa-bug fa-2x"></i>
                            <span class="nav-label">Support</span>
                        </a>
                    </li>
                    <li id="nav_logout" class="nav-click">
                        <a href="{{ url('/logout') }}" onclick="event.preventDefault();document.getElementById('logout-form').submit();">
                            <i class="fa fa-plug fa-2x"></i>
                            <span class="nav-label">Logout</span>
                        </a>
                        <form id="logout-form" action="{{ url('/logout') }}" method="POST" style="display: none;">
                            {{ csrf_field() }}
                        </form>
                    </li>
                    @endif
                </ul>
                </div>
            </div>
        </nav>
        <div id="page-wrapper" class="gray-bg">
            <div class="row border-bottom">
                <nav class="navbar navbar-static-top white-bg" role="navigation" style="margin-bottom: 0">
                    <div class="navbar-header">
                        <a class="navbar-minimalize minimalize-styl-2 btn btn-primary " href="#">
                            <i class="fa fa-bars"></i>
                        </a>
                    </div>
                    <ul class="nav navbar-top-links navbar-right">
                        @if(!Auth::guest())
                        <li>
                            <span class="nav-label m-r-sm text-muted welcome-message">{{ Auth::user()->name }} @yield('title')</span>
                        </li>

                        <li>
                            <a href="{{ url('/logout') }}" onclick="event.preventDefault();document.getElementById('logout-form').submit();">
                                <i class="fa fa-plug"></i>
                                <span class="nav-label">Logout</span>
                            </a>
                            <form id="logout-form" action="{{ url('/logout') }}" method="POST" style="display: none;">
                                {{ csrf_field() }}
                            </form>
                        </li>
                        @endif
                    </ul>

                </nav>
            </div>

            <div class="wrapper wrapper-content">
                @include('notifications') 
                @yield('content')
            </div>
        </div>
    </div>
    <img alt="Trafficroots Analysis Pixel" src="https://publishers.trafficroots.com/pixel/58daaf821381f" style="display: none;">
</body>

</html>
