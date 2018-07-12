@extends('layouts.app')
@section('title', 'Creatives')
@section('content')
    @if(Session::has('success'))
        <div class="alert alert-success">
            <h2>{{ Session::get('success') }}</h2>
        </div>
    @endif
<div class="container">
	<div class="row">	
		<div class="col-xs-12 col-md-10">
			<div class="panel panel-default">
				<a href="/manage_campaign/{{ $campaign->id }}" class="btn btn-primary btn-xs pull-right m-t m-r">
					<span class="fa fa-arrow-circle-left"></span>&nbsp;Back to Campaign</a>
                <h4 class="p-title">New Creative for Campaign {{ $campaign->id }} - {{ $campaign->campaign_name }}</h4>
				<input type="hidden" name="campaign_category" id="campaign_category" value="{{ $campaign->campaign_category }}">
				<input type="hidden" name="location_type" id="location_type" value="{{ $campaign->location_type }}">

				<div class="ibox-content">
					<div class="row">
						<div class"col-xs-12">
						<div class="col-xs-12">
							<h2 class="text-success"><strong>Add Creatives</strong></h2>
							<form name="creative_form" id="creative_form" class="form-horizontal" role="form" method="POST" action="{{ url('/creatives') }}">
							{{ csrf_field() }}
							<input type="hidden" name="campaign_id" id="campaign_id" value="{{ $campaign->id }}">
							<div class="media-selection">
								<div class="col-xs-12 col-md-6 b-r">
								  <h3>Step 1)</h3>
									<div class="col-xs-12 form-group{{ $errors->has('media_id') ? ' has-error' : '' }}" style="float:none;margin-bottom:0;">
										<p><h4>Select Existing or Uploaded Image&nbsp;&nbsp;<i class="fa fa-camera"></i></h4></p>
										<div class="col-xs-12 mediaOptions form-group{{ $errors->has('media_id') ? ' has-error' : '' }}">
											<div class="col-xs-1">
												<em class="fa fa-question-circle" data-toggle="tooltip" data-placement="top" title="Choose an Image from Corresponding Library Category" style="display:inline;"></em>
											</div>
											<div class="col-xs-11">
											<select id="media_id" class="form-control" name="media_id" required>
												<option value="">Choose</option>
												@foreach($media as $type)
													<option value="{{ $type->id }}">{{$type->media_name}}</option>
												@endforeach
											</select>

											@if ($errors->has('media_id'))
												<span class="help-block">
													<strong>{{ $errors->first('media_id') }}</strong>
												</span>
											@endif
											</div>
										</div>
									</div>
									<div class="createNew">
										<div class="col-xs-12">
											<h4 for="imgCreateNew">Add New Image to Library</h4>
											<button type="button" class="btn btn-xs btn-primary" id="addImage">
												<i class="fa fa-plus-square-o"></i>&nbsp;&nbsp;Add Image</button>
										</div>
									</div>
								</div>
								<div class="col-xs-12 col-md-6">
									<h3>Step 2)</h3>
									<div class="col-xs-12 form-group{{ $errors->has('link_id') ? ' has-error' : '' }}" style="float:none;margin-bottom:22px">
										<p><h4>Select Existing or Uploaded URL  &nbsp;<i class="fa fa-link"></i></h4></p>
										<div class="col-xs-12">
											<div class="col-xs-1">
									  		<em class="fa fa-question-circle" data-toggle="tooltip" data-placement="top" title="Choose a Link from Corresponding Library Category" style="display:inline;"></em>
											</div>
											<div class="col-xs-11 col-md-">
										  	<select id="link_id" class="form-control" name="link_id" required>
												<option value="">Choose</option>
												@foreach($links as $link)
												<option value="{{ $link->id }}">{{$link->link_name}}</option>
												@endforeach
											</select>

											@if ($errors->has('link_id'))
												<span class="help-block">
													<strong>{{ $errors->first('link_id') }}</strong>
												</span>
											@endif
											</div>
										</div>
									</div>
									<div class="createNew">
										<div class="col-xs-12">
										 	<h4 for="linkCreateNew">Add New URL To Library</h4>
										 	<button type="button" class="btn btn-xs btn-primary" id="addUrl">
												<i class="fa fa-plus-square-o"></i>&nbsp;&nbsp;Add URL</button>
										</div>
									</div>
								</div>
								<div class="clearfix"></div>
								<div class="col-xs-12" style="margin-top: 40px;">
                                  <h3>Step 3)</h3>
                                    <div class="form-group{{ $errors->has('description') ? ' has-error' : '' }}" style="margin:0;">
                                        <h4>Combine Image and URL</h4>
                                        <label for="description" class="col-md-3 control-label">
                                          <em class="fa fa-question-circle" data-toggle="tooltip" data-placement="top" title="Add description to unique Image and URL combination"></em>
                                          &nbsp;Creative Name:
                                        </label>
                                        <div class="col-md-6">
                                            <input id="description" type="text" class="form-control" maxlength="32" name="description" value="{{ old('description') }}" required autofocus>
											@if ($errors->has('description'))
												<span class="help-block">
													<strong>{{ $errors->first('description') }}</strong>
												</span>
											@endif
                                        </div>
                                    </div>
								</div>
							</div>
							<div class="hidden">
							@if ($folders)
							@if ($user->allow_folders && count($folders))              
							<div class="form-group{{ $errors->has('media') ? ' has-error' : '' }}">
								<label for="folder_id" class="col-md-4 control-label">Folder</label>

								<div class="col-sm-8">
									<select id="folder_id" class="form-control" name="folder_id">
									<option value="">Choose</option>
									@foreach($folders as $folder)
										<option value="{{ $folder->id }}">{{$folder->folder_name}}</option>

									@endforeach
									</select>

									@if ($errors->has('folder_id'))
										<span class="help-block">
											<strong>{{ $errors->first('folder_id') }}</strong>
										</span>
									@endif
								</div>
							</div>
							@endif
							@endif
							</div>
							<div class="form-group text-center">
								<div class="col-xs-12">
								<br><br>
								<input class="btn btn-primary btn-sm" type="submit" name="submit" id="submit">
							</div></div>
							</form>
						</div>
						</div>
					</div>
				</div>
        	</div>
		</div>
    </div>
	<div class="btn-hide">@include('media_upload')</div>
	<div class="btn-hide">@include('link_upload')</div>
</div>
<script type="text/javascript">
    jQuery(document).ready(function($){
		$(document).on('hidden.bs.modal', function(){
            reloadMedia();
        });
		
		$('#image_category_id').prop('disabled', true);
		$('#link_category_id').prop('disabled', true);
		$('#location_type_id').prop('disabled', true);
		
        $('#folder_id').change(function(){
            var check = parseInt($(this).val());
            if(check){
                $('#link_id').prop("disabled", true);
                $('#media_id').prop("disabled", true);
            }else{
                $('#link_id').prop("disabled", false);
                $('#media_id').prop("disabled", false);
            }
        });
		
		$('#addImage').click(function() {
			$('#addMedia').modal('toggle');
		});	
		
		$('#addUrl').click(function() {
			$('#addLink').modal('toggle');
		});	
		
		$('#addMedia .btn-primary').click(function( event ) {
			$("#image_category_id").prop("disabled", false);
			$("#location_type_id").prop("disabled", false); 
		});	

		$('#addLink .btn-primary').click(function( event ) {
			$("#link_category_id").prop("disabled", false); 
		});	
		
		$('form').submit(function() {
			$("#image_category_id").prop('disabled', true);
			$("#link_category_id").prop('disabled', true);
			$("#location_type_id").prop('disabled', true);
			$("#creative_form select").removeAttr("required");
			$("#creative_form input").removeAttr("required");
		});
		
		var getCampaign = $("#campaign_category").val();
		var getLoctionType = $("#location_type").val();
		$("#image_category_id option:eq(" + getCampaign + ")").attr('selected', 'selected');
		$("#link_category_id option:eq(" + getCampaign + ")").attr('selected', 'selected');
		$("#location_type_id option:eq(" + getLoctionType + ")").attr('selected', 'selected');
    });

	
	function reloadMedia(){
        var category = parseInt($('#campaign_category').val());
    var location_type = parseInt($('#location_type').val());
        if(category && location_type){
            var url = '/getmedia?category=' + category + '&location_type=' + location_type;
            $.getJSON(url, function(data){
                $('#folder_id').html(data.folders);
                $('#link_id').html(data.links);
                $('#media_id').html(data.media);
            });
        }else{
            $('#folder_id').html("<option value=''>Choose</option>");
            $('#link_id').html("<option value=''>Choose</option>");
            $('#media_id').html("<option value=''>Choose</option>");
        }
    }
</script>
   <script type="text/javascript">
       jQuery(document).ready(function ($) {
               $('.nav-click').removeClass("active");
               $('#nav_buyer').addClass("active");
       });
   </script>
@endsection
