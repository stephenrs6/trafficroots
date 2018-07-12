@extends('layouts.app')

@section('title','Admin Login')
@section('css')
@endsection

@section('content')
    @if(Session::has('success'))
        <div id="alert_div" class="alert alert-success">
            <h4>{{ Session::get('success') }}</h4>
        </div>
    @endif
    @if (count($errors) > 0)
        <div class="alert alert-danger">
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif
<div class="container">
    <div class="row">
        <div class="col-md-12">
            <div class="ibox">
                    <div class="ibox-title">Login As Another User</div>
                    <div class="ibox-content table-responsive">
                        @if (count($users))
                            <table class="table table-hover table-border table-striped table-condensed dataTable" name="users_table" id="users_table" width="100%">
                            <thead>
                            <tr><th>User Name</th><th>User Email</th><th>Date Created</th><th>Login</th></tr>
                            </thead>
                            <tbody>
                            @foreach ($users as $user)
                                <tr>
                                    <td>{{ $user->name }} </td>
                                    <td> {{ $user->email }} </td>
                                    <td> {{ Carbon\Carbon::parse($user->created_at)->toDayDateTimeString() }} </td>
				    <td><form name="login{{ $user->id }}" action="/tradmlogin" method="POST">
					{{csrf_field()}}
                                        <input type="hidden" name="login_user" id="login_user" value="{{ $user->id }}">
                                        <input type="submit" value="Login As" class="btn btn-xs btn-primary"></form>
                                    </td>
                                </tr>
                   
                            @endforeach
                   
                            </tbody>
                            </table>
                        @else
                            <h3>No Users Defined</h3> 
 
                        @endif
                    </div>
            </div>
        </div>
    </div>
</div>
<script type="text/javascript">
    jQuery(document).ready(function ($) {
        $('[data-toggle="popover"]').popover({
            html: true,
        });
        if($('#alert_div'))
        {
            $('#alert_div').fadeOut(1600, function(){

             });
	}
	$('.dataTable').DataTable({
		"oLanguage": {
		    "sSearch": "Search Table"
		}, pageLength: 20,
		responsive: true
	});	
    });

</script>
   <script type="text/javascript">
       jQuery(document).ready(function ($) {
	       $('.nav-click').removeClass("active");
	       $('#nav_admin').addClass("active");
       });
   </script>
@endsection

