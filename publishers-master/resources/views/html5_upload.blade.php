@extends('layouts.app')
@section('title', '- Folders')
@section('content')
    @if(Session::has('success'))
        <div class="alert alert-success">
            <h2>{{ Session::get('success') }}</h2>
        </div>
    @endif
<div class="container">
    <div class="row">
        <div class="col-md-12">
            <div class="ibox">
                <div class="ibox-title">Upload Media</div>

                <div class="ibox-content">
                <form name="media_form" id="media_form" class="form-horizontal" enctype="multipart/form-data" role="form" method="POST" action="{{ url('/folder') }}">
                {{ csrf_field() }}
                        <div class="col-md-2">&nbsp;</div><div class="col-md-10">
                        <p>For our Advanced Users, we offer HTML5 folders. You can upload a .zip file of your self-contained ad script.</p>
                        <p>All HTML5 Campaigns are CPM only and subject to review and approval.</p>
                        <p>Your Zip file must contain a valid file called 'index.html' in its root.</p>
                        <br /></div>
                        <div class="form-group{{ $errors->has('name') ? ' has-error' : '' }}">
                            <label for="folder_name" class="col-md-4 control-label">Folder Name</label>

                            <div class="col-md-6">
                                <input id="folder_name" type="text" class="form-control" name="folder_name" value="{{ old('folder_name') }}" required autofocus>

                                @if ($errors->has('folder_name'))
                                    <span class="help-block">
                                        <strong>{{ $errors->first('folder_name') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>                

                        <div class="form-group{{ $errors->has('category') ? ' has-error' : '' }}">
                            <label for="category" class="col-md-4 control-label">Category</label>

                            <div class="col-md-6">
                                <select id="category" class="form-control" name="category" required>
                                <option value="">Choose</option>
                                @foreach($categories as $type)
                                    <option value="{{ $type->id }}">{{$type->category}}</option>

                                @endforeach
                                </select>
                          
                                @if ($errors->has('category'))
                                    <span class="help-block">
                                        <strong>{{ $errors->first('category') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>
                        <div class="form-group{{ $errors->has('location_type') ? ' has-error' : '' }}">
                            <label for="location_type" class="col-md-4 control-label">Location Type</label>

                            <div class="col-md-6">
                                <select id="location_type" class="form-control" name="location_type" required>
                                <option value="">Choose</option>
                                @foreach($location_types as $type)
                                    <option value="{{ $type->id }}">{{$type->description}} - {{$type->width}}x{{$type->height}}</option>

                                @endforeach
                                </select>


                                @if ($errors->has('location_type'))
                                    <span class="help-block">
                                        <strong>{{ $errors->first('location_type') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>
                       <div class="form-group{{ $errors->has('image') ? ' has-error' : '' }}">
                            <label for="image" class="col-md-4 control-label">File</label>

                            <div class="col-md-6">
                                <input type="file" name="zfile" id="zfile" accept=".zip" required />     
                                @if ($errors->has('image'))
                                    <span class="help-block">
                                        <strong>{{ $errors->first('image') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>
                       <div class="form-group">
                            <label for="submit" class="col-md-4 control-label">Submit File</label>
                            <div class="col-md-6">
                                <input type="submit" name="submit" id="submit" />
                            </div>
                        </div>
                </form>
<p id="error1" style="display:none; color:#FF0000;">
Invalid File! Only Zip or GZip files accepted.
</p>
<p id="error2" style="display:none; color:#FF0000;">
Maximum File Size Limit is 3000kB.
</p> 

               </div>
            </div>
        </div>
    </div>
</div>
<script type="text/javascript">
    jQuery(document).ready(function($){
$('#image').bind('change', function() {
if ($('input:submit').attr('disabled',false)){
	$('input:submit').attr('disabled',true);
	}
var ext = $('#image').val().split('.').pop().toLowerCase();
if ($.inArray(ext, ['gif','png','jpg','jpeg']) == -1){
	$('#error1').slideDown("slow");
	$('#error2').slideUp("slow");
	a=0;
	}else{
	var picsize = (this.files[0].size);
	if (picsize > 3000000){
	$('#error2').slideDown("slow");
	a=0;
	}else{
	a=1;
	$('#error2').slideUp("slow");
	}
	$('#error1').slideUp("slow");
	if (a==1){
		$('input:submit').attr('disabled',false);
		}
}
});
    });

</script>
   <script type="text/javascript">
       jQuery(document).ready(function ($) {
               $('.nav-click').removeClass("active");
               $('#nav_pub').addClass("active");
       });
   </script>
@endsection
