@extends('layouts.app') 
@section('css')
<link href="{{ URL::asset('css/bootstrap.min.css') }}" rel="stylesheet">
<link href="{{ URL::asset('css/plugins/iCheck/custom.css') }}" rel="stylesheet">
<link href="{{ URL::asset('css/custom.css') }}" rel="stylesheet">
<link href="{{ URL::asset('css/style.css') }}" rel="stylesheet">
@endsection 

@section('content')
 <style>
     .navbar-static-top {
        display: none;
    }
    .navbar-static-side {
        display: none;
    }
    #page-wrapper {
        padding: 0;
        margin: 0;
    }
 </style>

<div class="login-container" style:"margin-bottom: 0px;">
    <div id="wrapper">
        <div id="page-wrapper" class="gray-bg tree-bg" style="margin: 0px;">
            <div class="wrapper wrapper-content animated fadeInRight">
                <div class="row">
                    <div class="col-md-6 col-md-offset-3 m-t-lg clearfix">
                        <div class="ibox-title"><h3>Register</h3>
                    </div>
                    <div class="ibox-content clearfix">
                        <form class="form-horizontal" role="form" id="registrationform" method="POST" action="{{ url('/register') }}">
                            <input type="hidden" name="_token" value="jEBYi6NttG1WHgPNSXH6mKg9G2e0AzS7rZuog1GX"> 
							<input type="hidden" name="formId" value="2W7UI+OZYzFhmO4xQQWvcQ==" />
                            <p>Please fill out your information below.</p><br>
                            {{ csrf_field() }} 
                            <!--
                                <div class="form-group" style="display:none;">
                                    <label for="selectRole" class="col-lg-2 control-label">Select role</label>
                                    <div class="col-lg-10">
                                        <select name="selectRole" class="form-control" required>
                                            <option value="">Select A Role</option>
                                            <option value="publisher">Publisher</option>
                                            <option value="advertiser">Advertiser</option>
                                            <option value="both">Both</option>
                                        </select>
                                    </div>
                                </div>
                            -->
                            <div class="form-group {{ $errors->has('name') ? ' has-error' : '' }}">
                                <label for="name" class="col-lg-2 control-label">Name</label>
                                <div class="col-lg-10">
                                    <input id="name" type="name" placeholder="Your Full Name" name="name" class="form-control"> 
                                    @if ($errors->has('name'))
                                    <span class="help-block m-b-none">{{ $errors->first('name') }}</span> 
                                    @endif
                                </div>
                            </div>
                            <div class="form-group {{ $errors->has('email') ? ' has-error' : '' }}">
                                <label for="email" class="col-lg-2 control-label">Email</label>
                                <div class="col-lg-10">
                                    <input id="email" type="email" placeholder="Your Email" name="email" class="form-control">
                                    @if ($errors->has('email'))
                                    <span class="help-block m-b-none">{{ $errors->first('email') }}</span> 
                                    @endif
                                </div>
                            </div>
                            <div class="form-group {{ $errors->has('phone') ? ' has-error' : '' }}">
                                <label for="company" class="col-lg-2 control-label">Company</label>
                                <div class="col-lg-10">
                                    <input id="company" type="text" placeholder="Your Company's Name" name="company" class="form-control">
                                    @if ($errors->has('company'))
                                    <span class="help-block m-b-none">{{ $errors->first('company') }}</span> 
                                    @endif
                                </div>
                            </div>
                            <div class="form-group {{ $errors->has('password') ? ' has-error' : '' }}">
                                <label for="password" class="col-lg-2 control-label">Password</label>
                                <div class="col-lg-10">
                                    <input id="password" type="password" placeholder="Password" name="password" class="form-control">
                                    @if($errors->has('password'))
                                    <span class="help-block m-b-none">{{ $errors->first('password') }}</span> 
                                    @endif
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="password-confirm" class="col-lg-2 control-label">Confirm</label>
                                <div class="col-lg-10">
                                    <input id="password-confirm" type="password" placeholder="Confirm password" name="password_confirmation" class="form-control">
                                </div>
                            </div>
                            <div class="form-group">
                                <div class="col-lg-offset-2 col-lg-10">
                                    <div class="g-recaptcha" data-sitekey="6LfwKzUUAAAAAECCj-_5tID_aAm3-oYxBspUTrw0"></div>
                                </div>
                            </div>
                            
                                <div id="StayConnected">
                                    <h3>Stay Connected</h3>
                                    <label class="col-xs-2 col-md-1 control-label"><i class="fa fa-linkedin-square"></i></label>
                                    <div class="col-xs-10 col-md-5"><input type="text" placeholder="LinkedIn Account" class="form-control"></div>
                                    <label class="col-xs-2 col-md-1 control-label"><i class="fa fa-facebook-square"></i></label>
                                    <div class="col-xs-10 col-md-5"><input type="text" placeholder="Facebook Account" class="form-control"></div>
                                    <label class="col-xs-2 col-md-1 control-label"><i class="fa fa-instagram"></i></label>
                                    <div class="col-xs-10 col-md-5">
                                        <input type="text" placeholder="Instragram Account" class="form-control">
                                    </div>
                                    <label class="col-xs-2 col-md-1 control-label"><i class="fa fa-twitter-square"></i></label>
                                    <div class="col-xs-10 col-md-5"><input type="text" placeholder="Twitter Account" class="form-control">
                                    </div>
                                </div>
                                <div class="col-xs-12 text-center"><br>
                                    <div class="form-group">
                                        <div class="checkbox i-checks"><label> 	
                                            <div class="icheckbox_square-green" required style="position: relative;">
                                                <input type="checkbox" required>
                                                <ins class="iCheck-helper"></ins>
                                            </div>
					    <a href="/terms"> Agree to the terms and Conditions</a></label>

                                            <p><a href="/privacy">Our Privacy Policy</a></p>
                                        </div>
                                    </div>
                                    <!--recaptcha-->
                                    <br>
                                    <div class="form-group">
                                        <button class="btn btn-primary" type="submit" value="Submit"><strong>Submit</strong></button>
                                        <button class="btn btn-danger" id="cancel"><strong>Cancel</strong></button>
                                    </div>
                                </div>
                                <hr>
                                <div class="text-center">
									<h4>or...login with</h4>
									<a href="{{ url('/auth/google') }}" class="btn btn-google"><i class="fa fa-google"></i> Google</a>
									<a href="{{ url('/auth/facebook') }}" class="btn btn-facebook"><i class="fa fa-facebook"></i> Facebook</a>
                                </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection 
 {{-- @section('content')
<div class="container">
    <div class="row">
        <div class="col-md-8 col-md-offset-2">
            <div class="ibox">
                <div class="ibox-title">Publisher Registration</div>
                <div class="ibox-content">
                    <form class="form-horizontal" role="form" method="POST" action="">
                        {{ csrf_field() }}

                        <div class="form-group{{ $errors->has('name') ? ' has-error' : '' }}">
                            <label for="name" class="col-md-4 control-label">Name</label>

                            <div class="col-md-6">
                                <input id="name" type="text" class="form-control" name="name" value="{{ old('name') }}" required autofocus> @if ($errors->has('name'))
                                <span class="help-block">
                                        <strong>{{ $errors->first('name') }}</strong>
                                    </span> 
                                    @endif
                            </div>
                        </div>

                        <div class="form-group{{ $errors->has('email') ? ' has-error' : '' }}">
                            <label for="email" class="col-md-4 control-label">E-Mail Address</label>

                            <div class="col-md-6">
                                <input id="email" type="email" class="form-control" name="email" value="{{ old('email') }}" required> @if ($errors->has('email'))
                                <span class="help-block">
                                        <strong>{{ $errors->first('email') }}</strong>
                                    </span> 
                                    @endif
                            </div>
                        </div>

                        <div class="form-group{{ $errors->has('phone') ? ' has-error' : '' }}">
                            <label for="phone" class="col-md-4 control-label">Phone</label>

                            <div class="col-md-6">
                                <input id="phone" type="phone" class="form-control" name="phone" value="{{ old('phone') }}"> @if ($errors->has('phone'))
                                <span class="help-block">
                                        <strong>{{ $errors->first('phone') }}</strong>
                                    </span> 
                                    @endif
                            </div>
                        </div>
                        <div class="form-group{{ $errors->has('password') ? ' has-error' : '' }}">
                            <label for="password" class="col-md-4 control-label">Password</label>

                            <div class="col-md-6">
                                <input id="password" type="password" class="form-control" name="password" required> @if ($errors->has('password'))
                                <span class="help-block">
                                        <strong>{{ $errors->first('password') }}</strong>
                                    </span> 
                                    @endif
                            </div>
                        </div>
						<div class="form-group">
							<textarea class="form-control" id="textarea" rows="8" cols="30" maxlength="99" ></textarea>
							<div id="textarea_feedback"></div>
						</div>

                        <div class="form-group">
                            <label for="password-confirm" class="col-md-4 control-label">Confirm Password</label>

                            <div class="col-md-6">
                                <input id="password-confirm" type="password" class="form-control" name="password_confirmation" required>
                            </div>
                        </div>

                        <div class="form-group">
                            <div class="col-md-6 col-md-offset-4">
                                <button type="submit" class="btn btn-primary">
                                    Register
                                </button>
                            </div>
                        </div>
						<hr>
						<div class="form-group">
							<div class="col-md-6 col-md-offset-3 text-center">
								<h4>or...login with</h4>
								<a href="{{ url('/auth/google') }}" class="btn btn-google"><i class="fa fa-google"></i> Google</a>
								<a href="{{ url('/auth/facebook') }}" class="btn btn-facebook"><i class="fa fa-facebook"></i> Facebook</a>
							</div>
						</div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection --}} 

@section('js')
<script src="js/plugins/iCheck/icheck.min.js"></script>
<script src="js/icheck.min.js"></script>
<script>
$(document).ready(function() {
    $('.i-checks').iCheck({
        checkboxClass: 'icheckbox_square-green',
        radioClass: 'iradio_square-green',
    });
	
	    var text_max = 99;
    $('#textarea_feedback').html(text_max + ' characters remaining');

    $('#textarea').keyup(function() {
        var text_length = $('#textarea').val().length;
        var text_remaining = text_max - text_length;

        $('#textarea_feedback').html(text_remaining + ' characters remaining');
    });
	
	$('.icheckbox_square-green input').css("opacity", "inherit");
	$('.icheckbox_square-green input').css("opacity", "0");
    setActiveNav('#nav_register');
	
	$("#cancel").click(function (e) {
		e.preventDefault();
		window.location.replace("/login");
	});

    //alert("test");
    $('.nav-click').removeClass("active");
    $('#nav_register').addClass("active");
    $(".no-skin-config").removeAttr("style");
});
</script>
@endsection
