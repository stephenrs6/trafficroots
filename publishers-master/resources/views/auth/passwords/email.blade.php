@extends('layouts.app')
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
    <div class="wrapper">
	<div id="page-wrapper" class="gray-bg tree-bg" style="margin: 0px;">
                   @if (session('status'))
                        <div class="alert alert-success">
                            {{ session('status') }}
                        </div>
                   @endif
            <div class="wrapper wrapper-content animated fadeInRight">
                <div class="row">
                    <div class="col-md-4 col-md-offset-4 m-t-lg" id="SignIN">
                        <div class="ibox-title"><h3>Forgot Password</h3>
						</div>
                        <div class="ibox-content">
				<div class="col-xs-12">
                            	<form class="form-horizontal" role="form" id="forgot-password" method="POST" action="{{ url('/password/email') }}">
                                {{ csrf_field() }}
					<div class="form-group {{ $errors->has('email') ? ' has-error' : '' }}">
					<label for="email">Email</label>
					<input type="email" placeholder="Email" name="email" class="form-control" required>
					@if ($errors->has('email'))
					<span class="help-block m-b-none">{{ $errors->first('email') }}</span>
					@endif
					</div>
					<div class="centered-block"><br>
					<button class="btn btn-primary" type="submit" value="Submit" id="submit"><strong>Send Email</strong></button>
					<button class="btn btn-danger" id="cancel"><strong>Cancel</strong></button>
					</div>
				</form>
				</div>
                        </div>
                    </div>
                </div>
	    </div>
        </div>
    </div>
</div>
@endsection

@section('js')
<script src="{{ URL::asset('js/plugins/sweetalert/sweetalert.min.js') }}"></script>
<script type="text/javascript">
    $(document).ready(function(){
        $('.no-skin-config').removeAttr('style');
		
		$('#page-wrapper').addClass("tree-bg");
		
		$("#cancel").click(function (e) {
			e.preventDefault();
			window.location.replace("/login");
		});
		
		$("#submit").click(function (e) {
			var isValid = $(e.target).parents('form').isValid();
			//if not prevent the default action from occurring 
    		if(!isValid) {
				e.preventDefault();
			} else {
				swal({
					title: "Email Sent",
					text: "Please check your email to reset password.", 
					icon: "success"
				}).then(() => {
					window.location.replace("/login");
				});
			}
			
			
				
		});
		
    });
	
</script>


@endsection
