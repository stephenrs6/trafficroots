@extends('layouts.app')
@section('title') 
@section('css')
<link href="css/custom.css" rel="stylesheet"> 

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

 <div class="login-container">
 <div id="wrapper">
        <div id="page-wrapper" class="gray-bg tree-bg" style="margin: 0px;">
            <div class="wrapper wrapper-content animated fadeInRight">
                <div class="row">
                    <div class="col-md-4 col-md-offset-4 m-t-lg" id="SignIN">
                        <div class="ibox-title"><h3>Sign In</h3>
						</div>
                        <div class="ibox-content">
                            <form role="form" method="POST" action="{{ url('/login') }}">
                                {{ csrf_field() }}

                                <div class="form-group{{ $errors->has('email') ? ' has-error' : '' }}">
                                    <label for="email">Email</label>
                                    <input type="email" placeholder="Enter Your Email" class="form-control" name="email" value="{{ old('email') }}" required>

                                    @if ($errors->has('email'))
	                                    <span class="help-block">
	                                        <strong>{{ $errors->first('email') }}</strong>
	                                    </span>
	                                @endif
                                </div>
                                <div class="form-group{{ $errors->has('password') ? ' has-error' : '' }}">
                                    <label for="password">Password</label>
                                    <input type="password" placeholder="Enter Your Password" class="form-control" name="password" required>

	                                @if ($errors->has('password'))
		                                 <span class="help-block">
		                                     <strong>{{ $errors->first('password') }}</strong>
		                                 </span>
                                 	@endif
                                </div>
                                <div>
                                    <label>
                                        <label>Remember Me </label> <input type="checkbox" name="remember" {{ old('remember') ? 'checked' : ''}}>
                                    </label>
									<label class="pull-right">
										<a href="{{ url('/password/reset') }}">Forgot Password?</a>
									</label>
                                </div>
								<div class="centered-block"><br>
									<button class="btn btn-primary block full-width m-b" type="submit" value="Submit"><strong>Sign In</strong></button>
								</div>
                            </form>
                        </div>
                    </div>
                    <div class="col-md-4 col-md-offset-4 m-t-lg text-center">
                        <span id="Or" class="or-line"><span class="or">Log In With</span></span>
                    </div>
                    <div class="col-md-4 col-md-offset-4 m-t-lg">
                        <div class="ibox-content clearfix">
                            <div class="form-group text-center" id="SocialSignIn">
								
								<br>
								<div id="LoginWith">
									<a href="{{ url('/auth/facebook') }}"><button class="loginBtn loginBtn--facebook btn-block">Facebook</button></a><br/>
									<a href="{{ url('/auth/google') }}"><button class="loginBtn loginBtn--google  btn-block" id="googlelogin">Google </button></a>
								</div>
								<br>
								<div>Don't have an account?
									<a href="register">Register Now</a>
								</div>
							</div>
						</div>
					</div>
                </div>
            </div>
        </div>
    </div>
 </div>


 @endsection
