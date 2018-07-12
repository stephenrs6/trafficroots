@extends('layouts.landing')
@section('title', '- Welcome!')
@section('content')


<div id="inSlider" class="carousel carousel-fade" data-ride="carousel">
    <ol class="carousel-indicators">
        <li data-target="#inSlider" data-slide-to="0" class="active"></li>
        <li data-target="#inSlider" data-slide-to="1"></li>
    </ol>
    <div class="carousel-inner" role="listbox">
        <div class="item active">
            <div class="container">
                <div class="carousel-caption">
                    <h1>Traffic Roots</h1>
                   
                    <p>Ad Network for Modern Lifestyles</p>
                    <p><a class="btn btn-xxxlg btn-primary" href="login" role="button">REGISTER NOW</a></p>
                </div>
                <div class="carousel-image wow zoomIn">
                    <img src="img/landing/laptop.png" alt="laptop"/>
                </div>
            </div>
            <!-- Set background for slide in css -->
            <div class="header-back one"></div>

        </div>
        <div class="item">
            <div class="container">
                <div class="carousel-caption blank">
                    <h1>Better Visibility Means <br /> Higher Traffic and Fatter Profits!.</h1>
                    <p>Traffic Roots connects the gap between digital display advertising and the modern lifestyle.<br /> We empower you to scale your digital advertising efforts by reaching more consumers,<br /> on reputable websites, and with minimum effort. <br />We built the bridge to infinite digital marketing opportunities – and we’re going to help you scale it, too.</p>
                    <p><a class="btn btn-lg btn-primary" href="login" role="button">Register Now</a></p>
                </div>
            </div>
            <!-- Set background for slide in css -->
            <div class="header-back two"></div>
        </div>
    </div>
    <a class="left carousel-control" href="#inSlider" role="button" data-slide="prev">
        <span class="glyphicon glyphicon-chevron-left" aria-hidden="true"></span>
        <span class="sr-only">Previous</span>
    </a>
    <a class="right carousel-control" href="#inSlider" role="button" data-slide="next">
        <span class="glyphicon glyphicon-chevron-right" aria-hidden="true"></span>
        <span class="sr-only">Next</span>
    </a>
</div>
<section id="features" class="container features">
    <div class="row">
        <div class="col-lg-12 text-center">
            <div class="navy-line"></div>
            <h1>You’ve already planted the seed. Now take your brand further!<br/> <span class="navy"> </span> </h1>
        </div>
    </div>
    <div class="row">
        <div class="col-md-3 text-center wow fadeInLeft">
            <div>
                <i class="fa fa-globe features-icon"></i>
                <h2>Global Coverage</h2>
                <p>We provide a global solution and monetize traffic from every country, worldwide. Traffic Roots helps you create ads tailored to your audience’s interests, habits, and location, with a 100% fill-rate.</p>
            </div>
            <div class="m-t-lg">
                <i class="fa fa-clock-o features-icon"></i>
                <h2>24/7 Customer Service</h2>
                <p>We’re here for you 24/7, because we’re more than just a service provider – we’re your partner, too. Day or night, we’ve got you covered.</p>
            </div>
        </div>
        <div class="col-md-6 text-center  wow zoomIn">
            <img src="img/landing/perspective.png" alt="dashboard" class="img-responsive">
        </div>
        <div class="col-md-3 text-center wow fadeInRight">
            <div>
                <i class="fa fa-flask features-icon"></i>
                <h2>Multiple Formats</h2>
                <p>We’ve designed a wide variety of web and mobile formats, so that you can find the best options for your business and maximize your ad revenue.</p>
            </div>
            <div class="m-t-lg">
                <i class="fa fa-dashboard features-icon"></i>
                <h2>Real-Time Statistics</h2>
                <p>Life doesn’t pause for data. Neither do we. Traffic Roots offers real-time, comprehensive statistics on your ads. Filter by geographic zone, or see how you’re doing on the whole.</p>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-lg-6 col-lg-offset-3 text-center">
            <div class="navy-line"></div>
            <h1>Tap Into Maximum Impressions!</h1>
        </div>
    </div>
</section>

<!--
<section id="team" class="features">
    <div class="row features-block">
        <div class="col-lg-6 features-text text-center wow fadeInLeft">
            <h2>Perfectly designed </h2>
            <p>Gain instant and exclusive access to the largest cannabis and vape-friendly websites from one single platform. Whether you’re looking to publish on the industry heavy-hitters, uniquely niche, both, or anything in between, we have the backstage pass to advertise on your favorite sites.</p>            
            <a href="" class="btn btn-primary">Learn more</a>
        </div>
        <div class="col-lg-6 text-right wow text-center fadeInRight">
            <img src="img/landing/dashboard.png" alt="dashboard" class="img-responsive pull-right">
        </div>
    </div>
</section>
--!>

<section id="team" class="gray-section team">
    <div class="container">
        <div class="row m-b-lg">
            <div class="col-lg-12 text-center">
                <div class="navy-line"></div>
                <h1>Our Team</h1>
                <p>Traffic Roots is built by a team of industry veterans who know what it is like to count daily impressions in the billions.  We bring together Executive Management, Systems Architecture and Javascript Wizardry. We built our self service platform to meet the needs of a wide variety of publishing and media verticals - from traditional, web based campaigns to closed circuit displays to emerging video ads and streaming television markets.  We can monetize your traffic.  If you've got a product or service to market, we can get you in front of the right eyeballs and drive your conversions like never before.  If you need help breaking into digital marketing for the first time, our support team can guide you and train you on our system.  If you are an expert, ready to start your own affiliate program on our network, we've got the tools and support for you, too.</p>
            </div>
        </div>
        <div class="row">
            <div class="col-sm-4 wow fadeInLeft">
                <div class="team-member">
                    <h1><i class="fa fa-gears fa-4x"></i></h1>
                </div>
            </div>
            <div class="col-sm-4">
                <div class="team-member wow zoomIn">
                    <h1><i class="fa fa-university fa-5x"></i></h1>
                </div>
            </div>
            <div class="col-sm-4 wow fadeInRight">
                <div class="team-member">
                    <h1><i class="fa fa-magic fa-4x"></i></h1>
                </div>
            </div>
        </div>
    </div>
</section>
<section id="joinus" class="features">
    <div class="container">
        <div class="row">
            <div class="col-lg-12 text-center">
                <div class="navy-line"></div>
                <h1>We want YOUR Traffic!</h1>
                <p>Join our network and access exclusive features, while adding your own distinctiveness to our environment.  The bigger we get, the better you will do.  Our sales team are relentlessly pursuing all related sites and products in an effort to present the most diverse lifestyle network in this space. Signup today and receive a limited time offer in your inbox! </p>
            </div>
        </div>
        <div class="row features-block">
            <div class="col-lg-3 features-text wow fadeInLeft">
                <h2>Publishers</h2>
                <p>Traffic Roots provides publishers with the unique opportunity to monetize their website by getting in front of thousands of advertisers, instantly. We cooked up an algorithm that will generate relevant, high quality ads on your website, specifically chosen for your audience. Whether you’re a leader of the masses or the meeting post for the niche, Traffic Roots provides ads that your visitors will find useful (and your wallet won’t mind it, either).
</p>
                <div><a href="" class="btn btn-primary" data-toggle="modal" data-target="#pubModal">Sign Up</a></div>
            </div>
            <div class="col-lg-6 text-right">
                <img src="img/landing/iphone.jpg" class="img-responsive" alt="dashboard">
            </div>
            <div class="col-lg-3 features-text text-right wow fadeInRight">
                <h2>Advertisers</h2>
                <p>Get a first-hand introduction to the largest audience of prospective buyers and clients. We meticulously developed our ad software to connect you to sites and consumers with the biggest buying potential for you, earning you more money without ever breaking a sweat. Running a digital ad campaign across a multi-channel network is easy and affordable with Traffic Roots.
</p>
                <div><a href="" class="btn btn-primary" data-toggle="modal" data-target="#buyerModal">Sign Up</a></div>
            </div>
        </div>
    </div>

<!-- modal windows -->
<!-- delayed pop modal -->
                            <div class="modal inmodal" id="popModal" tabindex="-1" role="dialog" aria-hidden="true">
                                <div class="modal-dialog">
                                <div class="modal-content animated bounceInLeft">
                                        <div class="modal-header">
                                            <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
                                            <i class="fa fa-laptop modal-icon"></i>
                                            <h4 class="modal-title">Publishers/Advertisers</h4>
                                            <small class="font-bold">Join us today!</small>
                                        </div>
                                        <form name="pop_form" id="pop_form" action="" method="POST">
                                        {{ csrf_field() }}
                                        <div id="pop_body" class="modal-body">
                                            <p>Sign up for our newsletter and find out how to maximize your earnings with the Traffic Roots Ad Network</p>
                                            <p>Register as a User at <a href="https://trafficroots.com/register" target="_blank">trafficroots.com</a> and get Bonus Cash added to your account!</p>
                                                    <div class="form-group"><label>First Name</label> <input type="text" name="first_name" id="first_name" placeholder="First name" class="form-control" required></div>
                                                    <div class="form-group"><label>Last Name</label> <input type="text" name="last_name" id="last_name" placeholder="Last name" class="form-control" required></div>
                                                    <div class="form-group"><label>Email</label> <input type="email" name="email" id="email" placeholder="Enter your email" class="form-control" required></div>
                                                    <div class="form-group"><label>About Me:</label> 
                                                        <select name="list_id" id="list_id" class="form-control listid" required>
                                                        <option value="">Choose One</option>
                                                        <option value="1">I'm a Publisher</option>
                                                        <option value="2">I'm an Advertiser</option>
                                                        </select>                                         
                                                    </div>
                                        </div>
                                        <div class="modal-footer" id="pop_footer">
                                            <button type="button" class="btn btn-white" data-dismiss="modal">Close</button>
                                            <button type="submit" id="subscribePop" class="btn btn-primary">Submit</button>
                                        </div>
                                        </form>
                                    </div>
                                </div>
                            </div>

                            <div class="modal inmodal" id="pubModal" tabindex="-1" role="dialog" aria-hidden="true">
                                <div class="modal-dialog">
                                <div class="modal-content animated bounceInRight">
                                        <div class="modal-header">
                                            <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
                                            <i class="fa fa-laptop modal-icon"></i>
                                            <h4 class="modal-title">Publishers</h4>
                                            <small class="font-bold">Join us today!</small>
                                        </div>
                                        <form name="publisher_form" id="publisher_form" action="" method="POST">
                                        {{ csrf_field() }}
                                        <div id="pub_body" class="modal-body">
                                            <p>Sign up for our newsletter and find out about all the opportunities to monetize your traffic and maximize your earnings with the Traffic Roots Ad Network</p>
 
                                                    <div class="form-group"><label>First Name</label> <input type="text" name="first_name" id="first_name" placeholder="First name" class="form-control" required></div>
                                                    <div class="form-group"><label>Last Name</label> <input type="text" name="last_name" id="last_name" placeholder="Last name" class="form-control" required></div>
                                                    <div class="form-group"><label>Email</label> <input type="email" name="email" id="email" placeholder="Enter your email" class="form-control" required></div>
                                        </div>
                                        <input type="hidden" name="list_id" id="list_id" value="1">
                                        <div class="modal-footer" id="pub_footer">
                                            <button type="button" class="btn btn-white" data-dismiss="modal">Close</button>
                                            <button type="submit" id="subscribePublisher" class="btn btn-primary">Submit</button>
                                        </div>
                                        </form>
                                    </div>
                                </div>
                            </div>

                            <div class="modal inmodal" id="buyerModal" tabindex="-1" role="dialog" aria-hidden="true">
                                <div class="modal-dialog">
                                <div class="modal-content animated bounceInRight">
                                        <div class="modal-header">
                                            <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
                                            <i class="fa fa-laptop modal-icon"></i>
                                            <h4 class="modal-title">Advertisers</h4>
                                            <small class="font-bold">Join us today!</small>
                                        </div>
                                        <form name="buyer_form" id="buyer_form" action="" method="POST">
                                        {{ csrf_field() }}
                                        <div id="buyer_body" class="modal-body">
                                                    <p>Sign up for our newsletter and stay informed about all the opportunities for marketing your product. Learn about our affiliate networks and how to start your own affiliate program!</p>
                                                    <div class="form-group"><label>First Name</label> <input type="text" name="first_name" id="first_name" placeholder="First name" class="form-control" required></div>
                                                    <div class="form-group"><label>Last Name</label> <input type="text" name="last_name" id="last_name" placeholder="Last name" class="form-control" required></div>
                                                    <div class="form-group"><label>Email</label> <input type="email" name="email" id="email" placeholder="Enter your email" class="form-control" required></div>
                                        
                                        </div>
                                        <input type="hidden" name="list_id" id="list_id" value="2">
                                        <div class="modal-footer" id="buyer_footer">
                                            <button type="button" class="btn btn-white" data-dismiss="modal">Close</button>
                                            <button type="submit" id="subscribeBuyer" class="btn btn-primary">Submit</button>
                                        </div>
                                        </form>
                                    </div>
                                </div>
                            </div>

</section>
<section id="traffic" class="features">
    <div class="container">
        <div class="row">
            <div class="col-lg-12 text-center">
                <div class="navy-line"></div>
                <h1>Our Traffic</h1>
                <p>Traffic Roots Gets Visitors From Around The Globe. Here's a real-time Breakdown of our Targeted Traffic: </p>
            </div>
        </div>
        <div class="row features-block">
            <div class="col-lg-6 features-text wow fadeInLeft">
                <h2>U.S. Traffic!</h2>
                <p>Traffic Breakdown: Top 20 States</p>
                {!! $us_display !!}
            </div>
            <div class="col-lg-6 features-text wow fadeInRight">
                <h2>International Traffic!</h2>
                <p>Traffic Breakdown: Top 20 Geos</p>
                {!! $geo_display !!}
            </div>
        </div>
    </div>


</section>	
<section id="contact" class="gray-section contact">
    <div class="container">
        <div class="row m-b-lg">
            <div class="col-lg-12 text-center">
                <div class="navy-line"></div>
                <h1>Contact Us</h1>
            </div>
        </div>
        <div class="row m-b-lg">
            <div class="col-lg-12 text-center">
                <address>
                    <strong><span class="navy">Traffic Roots, LLC.</span></strong><br/>

                    San Diego, CA 92121<br/>  

                </address>
            </div>
            <div class="col-lg-12 text-center">
                <div class="navy-line"></div>
            </div>
        </div>
        <div class="row">
            <div class="col-lg-12 text-center">
                <a href="mailto:info@trafficroots.com" class="btn btn-primary">Send us mail</a>
                <p class="m-t-sm">
                    Or follow us on social platform
                </p>
                <ul class="list-inline social-icon">
                    <li><a href="https://twitter.com/TrafficRoots" target="_blank"><i class="fa fa-twitter"></i></a>
                    </li>
                    <li><a href="https://www.facebook.com/trafficrootsmedia/" target="_blank"><i class="fa fa-facebook"></i></a>
                    </li>
                    <li><a href="https://www.linkedin.com/in/traffic-roots-44b648123/" target="_blank"><i class="fa fa-linkedin"></i></a>
                    </li>
                    <li><a href="https://instagram.com/traffic_roots" target="_blank"><i class="fa fa-instagram"></i></a>
                    </li>
                </ul>
            </div>
        </div>
        <div class="row">
            <div class="col-lg-8 col-lg-offset-2 text-center m-t-lg m-b-lg">
                <p><strong>&copy; <?php echo date('Y'); ?> Traffic Roots, LLC</strong></p>
            </div>
        </div>
    </div>
</section>

   <script type="text/javascript">
       jQuery(document).ready(function ($) {
        setTimeout(function(){ 
            var reg = readCookie('subscribed');
            if(reg == null){
                $('#popModal').modal('show');
            } 
        }, 10000);
        $('#buyer_form').submit(function(){
            var formdata = $('#buyer_form').serialize();
            $.post( "/buyer_subscribe", formdata)
                .done(function( data ) {
                    var response = JSON.parse(data);
                    if((response.response.success) && (response.response.success == "Subscriber added successfully")){
                        $('#buyer_body').fadeOut(function(){
                            $('#buyer_footer').html('');
                            $('#buyer_body').html('<h1>Thank You!</h1>');
                                $('#buyer_body').fadeIn(function(){
                                    createCookie('subscribed','true');
                                    setTimeout(function(){ $('#buyerModal').modal('hide'); }, 2000);
                                });
                            
                        });
                    }else{
                      alert(data);
                    }
                });
            return false;
        });
        $('#publisher_form').submit(function() {
            var formdata = $('#publisher_form').serialize();
            $.post( "/pub_subscribe", formdata)
                .done(function( data ) {
                    var response = JSON.parse(data);
                    if((response.response.success) && (response.response.success == "Subscriber added successfully")){
                        $('#pub_body').fadeOut(function(){
                            $('#pub_footer').html('');
                            $('#pub_body').html('<h1>Thank You!</h1>');
                                $('#pub_body').fadeIn(function(){
                                    createCookie('subscribed','true');
                                    setTimeout(function(){ $('#pubModal').modal('hide'); }, 2000);
                                });

                        });
                    }else{
                      alert(data);
                    }
                });
            return false;
        });
        $('#pop_form').submit(function() {
            var formdata = $('#pop_form').serialize();
            var list = $(this).find('.listid').val();
            var url = "";
            if(list == 1) url = "/pub_subscribe";
            if(list == 2) url = "/buyer_subscribe";
            if(url == "") return false; 
            $.post(url, formdata)
                .done(function( data ) {
                    var response = JSON.parse(data);
                    if((response.response.success) && (response.response.success == "Subscriber added successfully")){
                        $('#pop_body').fadeOut(function(){
                            $('#pop_footer').html('');
                            $('#pop_body').html('<h1>Thank You!</h1>');
                                $('#pop_body').fadeIn(function(){
                                    createCookie('subscribed','true');
                                    setTimeout(function(){ $('#popModal').modal('hide'); }, 2000);
                                });

                        });
                    }else{
                      alert(data);
                    }
                });
            return false;
        });        
        $('body').scrollspy({
            target: '.navbar-fixed-top',
            offset: 80
        });

        // Page scrolling feature
        $('a.page-scroll').bind('click', function(event) {
            var link = $(this);
            $('html, body').stop().animate({
                scrollTop: $(link.attr('href')).offset().top - 50
            }, 500);
            event.preventDefault();
        });
    });

    var cbpAnimatedHeader = (function() {
        var docElem = document.documentElement,
                header = document.querySelector( '.navbar-default' ),
                didScroll = false,
                changeHeaderOn = 200;
        function init() {
            window.addEventListener( 'scroll', function( event ) {
                if( !didScroll ) {
                    didScroll = true;
                    setTimeout( scrollPage, 250 );
                }
            }, false );
        }
        function scrollPage() {
            var sy = scrollY();
            if ( sy >= changeHeaderOn ) {
                $(header).addClass('navbar-scroll')
            }
            else {
                $(header).removeClass('navbar-scroll')
            }
            didScroll = false;
        }
        function scrollY() {
            return window.pageYOffset || docElem.scrollTop;
        }
        init();

    })();

    // Activate WOW.js plugin for animation on scrol
    new WOW().init();
        // Cookies
        function createCookie(name, value, days) {
            if (days) {
                var date = new Date();
                date.setTime(date.getTime() + (days * 24 * 60 * 60 * 1000));
                var expires = "; expires=" + date.toGMTString();
            }
            else var expires = "";               

            document.cookie = name + "=" + value + expires + "; path=/";
        }

        function readCookie(name) {
            var nameEQ = name + "=";
            var ca = document.cookie.split(';');
            for (var i = 0; i < ca.length; i++) {
                var c = ca[i];
                while (c.charAt(0) == ' ') c = c.substring(1, c.length);
                if (c.indexOf(nameEQ) == 0) return c.substring(nameEQ.length, c.length);
            }
            return null;
        }

        function eraseCookie(name) {
            createCookie(name, "", -1);
        }       
   </script>
   
@endsection
