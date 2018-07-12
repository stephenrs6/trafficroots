<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="_globalsign-domain-verification" content="qbw9lV17xS49YNux6uCCiE45peUkwMOWEjK5xjrONE" />
    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }} @yield('title', '')</title>

    <!-- Scripts -->
    <script>
    window.Laravel = <?php echo json_encode([
            'csrfToken' => csrf_token(),
        ]); ?>
    </script>

    <!-- Global and page level js -->
    @include('_landing_styles')
    @include('_landing_scripts')
</head>

<body id="page-top" class="landing-page no-skin-config">
<div class="navbar-wrapper">
        <nav class="navbar navbar-default navbar-fixed-top" role="navigation">
            <div class="container">
                <div class="navbar-header page-scroll">
                    <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#navbar" aria-expanded="false" aria-controls="navbar">
                        <span class="sr-only">Toggle navigation</span>
                        <span class="icon-bar"></span>
                        <span class="icon-bar"></span>
                        <span class="icon-bar"></span>
                    </button>
                            <a class="navbar-brand" href="{{ url('/') }}">
                               <div class="text-center">
                               <img src="{{ url('/img/white_tree_logo.png') }}"  alt="TrafficRoots" width="75" height="76" class="image1">
                               <img src="{{ url('/img/white_long_logo.png') }}"  alt="TrafficRoots" class="image2">
                               </div>
                            </a>
                </div>
                <div id="navbar" class="navbar-collapse collapse">
                    <ul class="nav navbar-nav navbar-right">
                        <li><a class="page-scroll" href="#page-top">Home</a></li>
                        <li><a class="page-scroll" href="#features">Features</a></li>
                        <li><a class="page-scroll" href="#team">Team</a></li>
                        <li><a class="page-scroll" href="#joinus">Join Us!</a></li>
                        <li><a class="page-scroll" href="#traffic">Traffic</a></li>
                        <li><a class="page-scroll" href="#contact">Contact</a></li>
                        <li><a href="login">Login / Register</a></li>
                    </ul>
                </div>
            </div>
        </nav>
</div>
@yield('content')
<img alt="Trafficroots Analysis Pixel" src="https://publishers.trafficroots.com/pixel/58daaf821381f" style="display: none;">
</body>

</html>
