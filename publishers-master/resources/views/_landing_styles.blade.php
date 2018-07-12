  <!-- Bootstrap core CSS -->
    <link href="{{ URL::asset('css/bootstrap.min.css') }}" rel="stylesheet">

    <!-- Animation CSS -->
    <link href="{{ URL::asset('css/animate.css') }}" rel="stylesheet">
    <link href="{{ URL::asset('font-awesome/css/font-awesome.min.css') }}" rel="stylesheet">

    <!-- Custom styles for this template -->
    <link href="{{ URL::asset('css/style.css') }}" rel="stylesheet">

<!-- tips styles -->
<style>
    .logo-style{
    width: 70px; height: 70px; border-radius: 50%; background-color: #19aa8d; display:table-cell; vertical-align:middle; text-align:center;
    }
    .image2{
       display: none;
    }


    @media only screen and (max-width: 500px){
       .image1{
         display: none;
       }
       .image2{
         display: block;
         height: 29px;
         width: 129px;
       }
       .landing-page .navbar-wrapper .navbar-header button {
         margin-top: 10px;
       }
       .landing-page .navbar .navbar-brand {
         margin: 10px 0 6px 0;
       }
       .landing-page .navbar.navbar-scroll .navbar-brand {
         margin: 10px 0 6px 0;
       }

    }
</style>
