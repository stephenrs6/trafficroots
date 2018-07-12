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

<body class="no-skin-config pace-done">
	<div class="pace  pace-inactive">
        <div class="pace-progress" data-progress-text="100%" data-progress="99" style="transform: translate3d(100%, 0px, 0px);">
            <div class="pace-progress-inner"></div>
        </div>
        <div class="pace-activity"></div>
    </div>
	
    <div id="wrapper">
        <nav class="navbar-default navbar-static-side" role="navigation">
            <div class="sidebar-collapse">
                <ul class="nav metismenu" id="side-menu">
					<li>
                        <div class="dropdown profile-element">
                            	<img src="{{ url('/img/logo.png') }}" alt="TrafficRoots" width="100%">
                        </div>
                        <div class="logo-element">
							<img src="{{ url('/img/white_tree_logo.png') }}" alt="TrafficRoots" width="40" height="42">
						</div>
                    </li>

                    <!-- Authentication Links -->
                    @if (Auth::guest())
                    <li id="nav_login" class="active nav-click">
                        <a href="{{ url('/login') }}">
                            <i class="fa fa-sign-in"></i>
                            <span class="nav-label">Login</span>
                        </a>
                    </li>
                    <li id="nav_register" class="nav-click">
                        <a href="{{ url('/register') }}">
                            <i class="fa fa-pencil"></i>
                            <span class="nav-label">Register</span>
                        </a>
                    </li>
                    {{--
                    <li id="nav_about" class="nav-click">
                        <a href="{{ url('/about') }}">
                            <i class="fa fa-group"></i>
                            <span class="nav-label">About</span>
                        </a>
                    </li> --}} @else
                    <li id="nav_pub" class="nav-click">
                        <a href="{{ url('/home') }}">
                            <i class="fa fa-desktop"></i>
                            <span class="nav-label">Publisher</span>
                            <span class="fa arrow"></span>
                        </a>
                        <ul id="nav_pub_menu" class="nav nav-second-level collapse">
                            <li id="nav_pub_dashboard" class "nav-click">
                                <a href="{{ URL::to('/home?type=1') }}">Dashboard</a>
                            </li>
                            <li id="nav_pub_sites" class="nav-click">
                                <a href="{{ URL::to('/sites') }}">Sites</a>
                            </li>
                            <li id="nav_pub_stats" class="nav-click">
                                <a href="{{ URL::to('/stats/pub') }}">Stats</a>
                            </li>
                        </ul>
                    </li>
                    <li id="nav_buyer" class="nav-click">
                        <a href="{{ url('/buyers') }}">
                            <i class="fa fa-bullhorn"></i>
                            <span class="nav-label">Advertiser</span>
                            <span class="fa arrow"></span>
                        </a>
                        <ul id="nav_buyer_menu" class="nav nav-second-level collapse">
                            <li id="nav_buyer_dashboard" class="nav-click">
                                <a href="{{URL::to('/home?type=2')}}">Dashboard</a>
                            </li>
                            <li id="nav_buyer_campaigns" class="nav-click">
                                <a href="{{URL::to('/campaigns')}}">Campaigns</a>
                            </li>
                            <li id="nav_buyer_library" class="nav-click">
                                <a href="{{URL::to('/library')}}">Library</a>
                            </li>
                        </ul>
                    </li>
		    <li id="nav_profile" class="nav-click">
                        <a href="{{ url('/profile') }}">
                            <i class="fa fa-address-book-o"></i>
                            <span class="nav-label">Profile</span>
                        </a>
                    </li>
                    <li id="nav_support" class="nav-click">
                        <a href="{{ url('/tickets') }}">
                            <i class="fa fa-bug"></i>
                            <span class="nav-label">Support</span>
                        </a>
                    </li>
					<li id="nav_buyer_faq" class="nav-click">
						<a href="{{URL::to('faq_advertiser')}}">
							<i class="fa fa-info-circle"></i>
							<span class="nav-label"> FAQs</span>
						</a>
					</li>
                @if(Auth::user()->allow_folders)
                    <li id="nav_admin" class="nav-click">
                        <a href="{{ url('/tradm') }}">
                            <i class="fa fa fa-address-card-o"></i>
                            <span class="nav-label">Admin</span>
                        </a>
                    </li>



                @endif
		@endif
				</ul>
			</div>
		</nav>
        <div id="page-wrapper" class="gray-bg" style="min-height: 755px;">
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
                            <span class="nav-label m-r-sm text-muted welcome-message">{{ Auth::user()->name }}</span>
                        </li>
						<li>
							<a href="{{ url('/logout') }}" onclick="event.preventDefault();document.getElementById('logout-form').submit();">
                            	<i class="fa fa-sign-out"></i>
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
			<div class="row">
                <span class="title-blue">@yield('title')</span>
            </div>
            <div class="wrapper wrapper-content animated fadeInRight">
                @include('notifications')
                @yield('content')
            </div>
        </div>
    </div>
    <img alt="Trafficroots Analysis Pixel" src="//publishers.trafficroots.com/pixel/58daaf821381f" style="display: none;">
</body>

</html>
