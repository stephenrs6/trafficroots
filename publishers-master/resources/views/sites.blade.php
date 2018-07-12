<?php
use App\Site;
?>
@extends('layouts.app')

@section('title','Publisher Sites/Zones')

@section('css')
<link href="//cdnjs.cloudflare.com/ajax/libs/highlight.js/9.12.0/styles/github.min.css" rel="stylesheet">
<link href="{{ URL::asset('css/plugins/footable/footable.core.css') }}" rel="stylesheet">
<link href="{{ URL::asset('css/plugins/iCheck/custom.css') }}" rel="stylesheet">
<link href="{{ URL::asset('css/style.css') }}" rel="stylesheet">
<link href="{{ URL::asset('css/custom.css') }}" rel="stylesheet">

<style type="text/css">
.footable th:last-child .footable-sort-indicator {
    display: none;
}
	
button span.btn-label {
    padding: 2px 8px;
    background: rgba(0,0,0,0.15);
    border-radius: 3px 0 0 3px;
    position: relative;
    left: -7px;
}
</style>
<link rel="stylesheet"
      href="{{ URL::asset('css/plugins/select2/select2.min.css') }}">
<link rel="stylesheet"
      href="{{ URL::asset('css/plugins/chosen/chosen.css') }}">
@endsection

@section('js')
<script src="//cdnjs.cloudflare.com/ajax/libs/highlight.js/9.12.0/highlight.min.js"></script>
<script src="{{ URL::asset('js/plugins/footable/footable.all.min.js') }}"></script>
<script>
hljs.initHighlightingOnLoad();
</script>
<script src="{{ URL::asset('js/plugins/iCheck/icheck.min.js') }}"></script>
<script src="{{ URL::asset('js/plugins/select2/select2.full.min.js') }}"></script>
<script src="{{ URL::asset('js/plugins/chosen/chosen.jquery.js') }}"></script>
<script src="js/plugins/sweetalert/sweetalert.min.js"></script>
@endsection

@section('content')
<div class="content">
<!--
	@if(sizeof($pending))
	<div class="row">
		<div class="col-xs-12">
			<div class="ibox">
				<div class="ibox-title"><h4>Pending Campaigns</h4></div>
				<div class="ibox-content">
					<div class="tableSearchOnly">
						<table class="tablesaw tablesaw-stack table-striped table-hover dataTableSearchOnly dateTableFilter" data-tablesaw-mode="stack">
							<thead>
								<tr>
									<th>Site</th>
									<th>Campaign</th>
									<th>Advertiser</th>
									<th class="col-xs-12 col-md-4">Options</th>
								</tr>
							</thead>
							<tbody>
								@foreach ($pending as $pend)
								<tr>
									<td class="text-center"><b class=" tablesaw-cell-label">Site</b>{{ $pend->site_name }}</td>
									<td class="text-center"><b class=" tablesaw-cell-label">Campaign</b>{{ $pend->campaign_name }}</td>
									<td class="text-center"><b class=" tablesaw-cell-label">Advertiser</b>{{ $pend->name }}</td>
									<td class="text-center"><b class=" tablesaw-cell-label">Options</b>
										<button class="btn btn-xs btn-success activate-bid" id="activate_bid_{{ $pend->id }}">
											<span class="btn-label"><i class="fa fa-check-square-o"></i></span>
											Activate</button>&nbsp;

										<a href="/preview/{{ $pend->id }}" target="_blank"><button class="btn btn-xs btn-primary alert-info">
											<span class="btn-label"><i class="fa fa-camera"></i></span>
											Preview</button></a>&nbsp;

										<button class="btn btn-xs btn-danger alert-danger decline-bid" id="decline_bid_{{ $pend->id }}">
											<span class="btn-label"><i class="fa fa-times"></i></span> 
											Decline
										</button>
									</td>
								</tr>
								@endforeach
							</tbody>
						</table>
					</div>
				</div>
			</div>
		</div>
	</div>
	@endif
-->
	<!--Add new Site -->
	<div class="row">
		<div class="col-xs-12">
			<div class="ibox">
				<div class="panel panel-default">
					<button type="button"
							class="btn btn-xs btn-primary pull-right m-t m-r"
							data-toggle="modal"
							data-target="#addSite"><span class="fa fa-plus-square-o"></span>&nbsp;&nbsp; Add Site</button>
					<h4 class="p-title">Sites</h4>
					<!--Add Site Modal-->
					<div class="modal inmodal"
						 id="addSite"
						 tabindex="-1"
						 role="dialog"
						 aria-hidden="true">
						<div class="modal-dialog">
						<div class="modal-content animated fadeIn">
							<div class="modal-header">
								<button type="button"
										class="close"
										data-dismiss="modal">
									<span aria-hidden="true">&times;</span>
									<span class="sr-only">Close</span>
								</button>
								<h4 class="modal-title"><i class="fa fa-plus-square-o"></i> New site</h4>
							</div>
							<form name="site_form"
								  id="site_form"
								  action="{{ url('/sites') }}"
								  method="POST">
								<div class="modal-body">
									{{ csrf_field() }}
									<div class="form-group">
										<label>Name</label>
										<input type="text"
											   placeholder="Enter your site name"
											   class="form-control"
											   name="site_name"
											   required>
										<label class="error hide"
											   for="site_name"></label>
									</div>
									<div class="form-group">
										<label>Url</label>
										<input type="text"
											   placeholder="Must be a valid URL, and include http:// or https://"
											   class="form-control"
											   name="site_url"
											   required>
										<label class="error hide"
											   for="site_url"></label>
									</div>
									<div class="form-group">
										<label>Site Theme</label>
										<select class="form-control m-b chosen-select" name="site_theme" placeholder="What kind of site is this?" required>
											<option value="">What kind of site is this?</option>
											@foreach($themes as $theme)
											<option value="{{ $theme->id }}">{{ $theme->theme }}</option>
											@endforeach
										</select>
										<label class="error hide"
											   for="site_theme"></label>
									</div>
									<div class="form-group">
										<label for="allowed_category[]">Advertising Categories Allowed</label>
										<select class="form-control m-b chosen-select"
												name="allowed_category[]"
						id="allowed_category[]"
												placeholder="Select Categories Allowed on this Site"
												multiple
												required>
											@foreach($categories as $category)
											<option value="{{ $category->id }}" selected>{{ $category->category }}</option>
											@endforeach
										</select>
										<button type="button" class="chosen-toggle select btn-xs btn-success">Select all</button>
										<button type="button" class="chosen-toggle deselect btn-xs btn-success">Deselect all</button>
										<label class="error hide"
											   for="allowed_category[]"></label>
									</div>
									<div class="form-group">
										<label>
											<input type="checkbox"
												   class="i-check"
												   name="zone_create">
											<i></i> Automatically create standard Zones for me
										</label>
										<label class="error hide"
											   for="zone_create"></label>
									</div>
								</div>
								<div class="modal-footer">
									<button type="button"
											class="btn btn-white"
											data-dismiss="modal">Cancel</button>
									<button type="submit"
											class="btn btn-primary">Save changes</button>
								</div>
							</form>
						</div>
					</div>
					</div>
					<div class="ibox-content">	
					<div class="tableSearchOnly">
						<table class="tablesaw tablesaw-stack table-striped table-hover dataTableSearchOnly dateTableFilter" data-tablesaw-mode="stack">
							<thead> 
							<tr>
								<th>Site Name</th>
								<th>Site Url</th>
								<th>Site Category</th>
								<th>Options</th>
							</tr>
							</thead>
							<tbody>
								@foreach ($sites as $site)
								<tr>
									<td class="text-center"><b class=" tablesaw-cell-label">Site Name</b><div>{{ $site->site_name }}</div></td>
									<td class="text-center col-xs-12 col-md-3"><b class=" tablesaw-cell-label">Site Url</b><div>{{ $site->site_url }}</div></td>
									<td class="text-center"><b class=" tablesaw-cell-label">Site Theme</b><div>{{ App\SiteTheme::where('id',$site->site_theme)->first()->theme }}</div></td>
									<td class="text-center" 
										data-site_id="{{ $site->id }}">
										<b class=" tablesaw-cell-label">Options</b>
										<div>
										<a href="#"
										   class="site-zones">
											<button class="btn btn-xs alert-info">
											<span class="btn-label">
												<i class="fa fa-map"></i> 
											</span> Zones</button>
										</a>
										<a href="{{ url("stats/site/".$site->id) }}" class="site-stats">
											<button class="btn btn-xs btn-warning alert-warning">
											<span class="btn-label">
												<i class="fa fa-line-chart"></i>
											</span> Stats</button>
										</a>
										<a href="#"
										   class="site-edit"
										   data-toggle="modal"
										   data-target="#editSite{{ $site->id }}">
											<button class="btn btn-xs btn-success alert-success">
											<span class="btn-label">
												<i class="fa fa-edit"></i>
											</span> Edit</button>
										</a>
										<a href="#"
										   class="site-pixel"
										   data-toggle="modal"
										   data-target="#sitePixel{{ $site->id }}">
											<button class="btn btn-xs btn-danger alert-info">
											<span class="btn-label">
												<i class="fa fa-file-code-o"></i>
											</span> Pixel</button>
										</a>
										</div>
									</td>
								</tr>
								@endforeach
							</tbody>
						</table>
					</div>
				</div>
				</div>
			</div>
		</div>
	</div>
		
	@foreach($sites as $site)
    <div class="row zones hide"
         id="zones{{ $site->id }}">
		
		<div class="col-xs-12">
			<div class="ibox">					
				<div class="panel panel-default">
					<button type="button"
                                class="btn btn-xs btn-primary pull-right m-t m-r"
                                data-toggle="modal"
                                data-target="#addZone{{ $site->id }}"><span class="fa fa-plus-square-o"></span>&nbsp;&nbsp; Add Zone</button>
					<h4 class="p-title">Zone Name: {{ $site->site_name }} - Based on Site</h4>
				  	<!--button modal-->
					<div class="modal inmodal"
						 id="addZone{{ $site->id }}"
						 tabindex="-1"
						 role="dialog"
						 aria-hidden="true">
						<div class="modal-dialog">
							<div class="modal-content animated fadeIn">
								<div class="modal-header">
									<button type="button"
											class="close"
											data-dismiss="modal">
										<span aria-hidden="true">&times;</span>
										<span class="sr-only">Close</span>
									</button>
									<h4 class="modal-title"><i class="fa fa-plus-square-o"></i> New Zone</h4>
								</div>
								<form name="zone_form"
									  id="zone_form"
									  action="{{ url("sites/$site->id/zones") }}" method="POST">
									<div class="modal-body">
										{{ csrf_field() }}
										<div class="form-group">
											<label>Name</label>
											<input type="text"
												   placeholder="Enter your zone name"
												   value="{{ old('zone_name') }}"
												   class="form-control"
												   name="description"
												   required>

											<label class="error hide"
												   for="zone_name"></label>
										</div>
										<div class="form-group">
											<label>Type</label>
											<select class="form-control m-b chosen-select"
													value="{{ old('location_type') }}"
													name="location_type"
													required>
												<option value="">Choose zone type</option>
												@foreach($locationTypes as $locationType)
												<option value="{{ $locationType->id }}">{{ $locationType->width . 'x' . $locationType->height . ' ' . $locationType->description }}</option>
												@endforeach
											</select>

											<label class="error hide"
												   for="location_type"></label>
										</div>
									</div>
									<div class="modal-footer">
										<button type="button"
												class="btn btn-white"
												data-dismiss="modal">Cancel</button>
										<button type="submit"
												class="btn btn-primary">Save changes</button>
									</div>
								</form>
							</div>
						</div>
					</div>
					<div class="ibox-content">
					<div class="tableSearchOnly">
						<table id="zonesTable" class="tablesaw tablesaw-stack table-striped table-hover dataTableSearchOnly dateTableFilter" data-tablesaw-mode="stack">
							<thead>
								<tr>
									<tr>
										<th>Zone Name</th>
										<th>Location Type</th>
										<th>Size</th>
										<th>Status</th> <!--Should toggle between active/inactive -->
										<th>Options</th>
									</tr>
							</thead>
							<tbody>
								@foreach ($site->zones as $zone)
								<tr>
									<td class="text-center col-xs-12 col-md-2"><b class=" tablesaw-cell-label">Zone Name</b><div>{{ $zone->description }}</div></td>
									<td class="text-center col-xs-12 col-md-2"><b class=" tablesaw-cell-label">Location Type</b><div>{{ $locationTypes->where('id',$zone->location_type)->first()->description }}</div></td>
									<td class="text-center col-xs-12 col-md-2"><b class=" tablesaw-cell-label">Size</b><div>{{ $locationTypes->where('id',$zone->location_type)->first()->width . 'x' . $locationTypes->where('id',$zone->location_type)->first()->height }}</div></td>
									<td class="text-center col-xs-12 col-md-2"><b class=" tablesaw-cell-label">Status</b>
										@if($zone->status == 1)
											<span class="label label-info">Active</span>
										@else
											<span class="label label-danger">Inactive</span>
										@endif
									</td> <!--Should toggle between active/inactive -->
									<td class="text-center col-xs-12 col-md-4"
										data-zone_id="{{ $zone->id }}">
										<b class=" tablesaw-cell-label">Options</b>
										<div>
										<a href="/stats/zone/{{ $zone->id }}"
										   class="zone-stats">
											<button class="btn btn-xs btn-warning"><span class="btn-label"><i class="fa fa-line-chart"></i></span> Stats</button>
										</a>
										<a href="#"
										   class="zone-edit"
										   data-toggle="modal"
										   data-target="#editZone{{ $zone->id }}">
											<button class="btn btn-xs btn-success"><span class="btn-label"><i class="fa fa-edit"></i></span> Edit</button>
										</a>

										<a href="#"
										   class="zone-code letest"
										   data-toggle="modal"
										   data-target="#zoneCode{{ $zone->id }}" style="color: white;">
											<button class="btn btn-xs btn-danger"><span class="btn-label"><i class="fa fa-file-code-o"></i></span> Code</button>
										</a>
                                                                                @if($user->allow_folders)
										<a href="/zone_manage/{{$zone->handle}}"
										   style="color: white;">
											<button class="btn btn-xs btn-info"><span class="btn-label"><i class="fa fa-wrench"></i></span> Manage</button>
										</a>
                                                                                @endif
										</div>
									</td>
								</tr>
								@endforeach
							</tbody>
                    	</table>
					</div>
				</div>
				</div>
			</div>
		</div>
	</div><!--row-->
    <div class="modal inmodal"
         id="sitePixel{{ $site->id }}"
         tabindex="-1"
         role="dialog"
         aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content animated fadeIn">
                <div class="modal-header">
                    <button type="button"
                            class="btn close"
                            data-dismiss="modal">
                        <span aria-hidden="true">&times;</span>
                        <span class="sr-only">Close</span>
                    </button>
                    <h4 class="modal-title"><i class="fa fa-file-code-o"></i> Site Analysis Pixel</h4>
                </div>
                <div class="modal-body">
                    <h3>Your Traffic Roots Analysis Pixel</h3>
                    <div style="overflow-wrap: break-word;">
                        <pre><code class="html">{{ htmlspecialchars('<img alt="Traffic Roots Pixel" src="//trafficroots.com/pixel/'.$site->site_handle.'" style="display:none;">') }}
                        </code></pre>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="modal inmodal"
         id="editSite{{ $site->id }}"
		 tabindex="-1"
         role="dialog"
         aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content animated fadeIn">
                <div class="modal-header">
                    <button type="button"
                            class="btn close"
                            data-dismiss="modal">
                        <span aria-hidden="true">&times;</span>
                        <span class="sr-only">Close</span>
                    </button>
                    <h4 class="modal-title"><i class="fa fa-edit"></i> Edit Site</h4>
                </div>
                <form name="site_form"
                      id="site_form"
                      action="{{ url("sites/$site->id") }}" method="POST"> {{ method_field('PATCH') }}
                    <div class="modal-body">
                        {{ csrf_field() }}
						<!-- <div class="pull-right">
							<button class="btn btn-xs btn-danger deleteSite">Delete Site
							</button>
						</div> -->
						<br>
                        <div class="form-group">
                            <label>Name</label>
                            <input type="text"
                                   placeholder="Enter your site name"
                                   value="{{ $site->site_name }}"
                                   class="form-control"
                                   name="site_name"
                                   required>

                            <label class="error hide"
                                   for="site_name"></label>
                        </div>
                        <div class="form-group">
                            <label>Url</label>
                            <input type="text"
                                   placeholder="Must be a valid URL, and include http:// or https://"
                                   value="{{ $site->site_url }}"
                                   class="form-control"
                                   name="site_url"
                                   required>

                            <label class="error hide"
                                   for="site_url"></label>
                        </div>
                        <div class="form-group">
                            <label>Site Theme</label>
                            <select class="form-control m-b chosen-select"
                                    value="{{ $site->site_theme }}"
                                    name="site_theme"
                                    required>
                                @foreach($themes as $theme)
                                <option @if($theme->id == $site->site_theme) selected="selected" @endif value="{{ $theme->id }}">{{ $theme->category }}</option>
                                @endforeach
                            </select>

                            <label class="error hide"
                                   for="site_theme"></label>
			</div>

                                        <div class="form-group">
                                            <label for="allowed_category[]">Advertising Categories Allowed</label>
                                            <select class="form-control m-b chosen-select"
                                                    name="allowed_category[]"
						    id="allowed_category[]"
                                                    placeholder="Select Categories Allowed on this Site"
                                                    multiple
                                                    required>
                                                @foreach($categories as $category)
                                                <option @if(Site::join('site_category', 'sites.id', '=', 'site_category.site_id')->where('sites.id', $site->id)->where('site_category.category', $category->id)->count() > 0) selected="selected" @endif value="{{ $category->id }}">{{ $category->category }}</option>
                                                @endforeach
                                            </select>
<button type="button" class="chosen-toggle select btn-xs btn-success">Select all</button>
                							<button type="button" class="chosen-toggle deselect btn-xs btn-success">Deselect all</button>
                                            <label class="error hide"
                                                   for="allowed_category[]"></label>
                                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button"
                                class="btn btn-white"
                                data-dismiss="modal">Cancel</button>
                        <button type="submit"
                                class="btn btn-primary">Save changes</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
		@foreach($site->zones as $zone)
		<?php 
			$width = $locationTypes->where('id',$zone->location_type)->first()->width; 
			$height = $locationTypes->where('id',$zone->location_type)->first()->height;
		?>
		<div class="modal inmodal"
			 id="editZone{{ $zone->id }}"
			 tabindex="-1"
			 role="dialog"
			 aria-hidden="true">
			<div class="modal-dialog">
				<div class="modal-content animated fadeIn">
					<div class="modal-header">
						<button type="button"
								class="close"
								data-dismiss="modal">
							<span aria-hidden="true">&times;</span>
							<span class="sr-only">Close</span>
						</button>
			<h4 class="modal-title"><i class="fa fa-edit"></i> Edit Zone</h4>
					</div>
					<form name="site_form"
						  id="site_form"
						  action="{{ url("zones/$zone->id") }}" method="POST"> {{ method_field('PATCH') }}
						<div class="modal-body">
							{{ csrf_field() }}
							<!-- <div class="pull-right">
								<button class="btn btn-xs btn-danger deleteZone">Delete Zone
								</button>
							</div> -->
							<br>
							<div class="form-group">
								<label>Name</label>
								<input type="text"
									   placeholder="Enter your zone name"
									   value="{{ $zone->description }}"
									   class="form-control"
									   name="description"
									   required>

								<label class="error hide"
									   for="description"></label>
							</div>
							<div class="form-group">
								<label>Type</label>
								<div>{{ $locationTypes->where('id',$zone->location_type)->first()->description }}</div>
							</div>
							<div class="form-group">
								<label>Size</label>
								<div>{{ $locationTypes->where('id',$zone->location_type)->first()->width . 'x' . $locationTypes->where('id',$zone->location_type)->first()->height }}</div>
							</div>
						</div>
						<div class="modal-footer">
							<button type="button"
									class="btn btn-white"
									data-dismiss="modal">Cancel</button>
							<button type="submit"
									class="btn btn-primary">Save changes</button>
						</div>
					</form>
				</div>
			</div>
		</div>
		<div class="modal inmodal"
			 id="zoneCode{{ $zone->id }}"
			 tabindex="-1"
			 role="dialog"
			 aria-hidden="true">
			<div class="modal-dialog">
				<div class="modal-content animated fadeIn">
					<div class="modal-header">
						<button type="button"
								class="close"
								data-dismiss="modal">
							<span aria-hidden="true">&times;</span>
							<span class="sr-only">Close</span>
						</button>
						<h4 class="modal-title"><i class="fa fa-file-code-o"></i> Zone Invocation Code</h4>
					</div>
					<div class="modal-body">
						<h3>Place this code in your site's layout:</h3>
						<div style="overflow-wrap: break-word;">
							<pre><code class="html">{{ htmlspecialchars('<div class="tr_'.$zone->handle.'" data-width="'.$width.'" data-height="'.$height.'"><script>var tr_handle = "'.$zone->handle.'";</script><script src="//service.trafficroots.com/js/service.js"></script></div>') }}
							</code></pre>
						</div>
					</div>
				</div>
			</div>
		</div>
		@endforeach
	@endforeach
</div>
<script type="text/javascript">
	$(document).ready(function(){
	$('.activate-bid').click(function() {
		swal({
			title: "Activate Campaign",
			text: "Do you want to activate this campaign?", 
			 icon: "success",
			buttons: true,
		}).then((isConfirm) => {
			if (isConfirm) {
				var str =  $(this).attr('id');
				var res = str.split("_");
				var url = '/activate_bid/' + res[2];
				$.get(url)
					.done(function (response) {
						toastr.success(response, function(){
						  setTimeout(function(){ window.location.reload(); }, 3000);
						});
					})
					.fail(function (response) {
						toastr.error(response);
					});
			} else {
				return false;
			}
		});	
	}); //end of activate-bid   
	
	//Pending Campaigns decline option, show sweet alert...
	//if approved the bid will be declined and refresh page
	$('.decline-bid').click(function() {	
		swal({
			title: "Cancel Campaign",
			text: "Are you sure you want to cancel this campaign?", 
			icon: "warning",
			buttons: true,
			dangerMode: true,
		}).then((cancel) => {
			if (cancel) {
				var str =  $(this).attr('id');
				var res = str.split("_");
				var url = '/decline_bid/' + res[2];
				$.get(url)
					.done(function (response) {
						toastr.success(response, function(){
						  setTimeout(function(){ window.location.reload(); }, 3000);
						});
					})
					.fail(function (response) {
						toastr.error(response);
					});
			} else {
				return false;
			}
		});

	});	//end of decline bid 
	
	//Delete the site 
	$(".delete-site").click(function() {	
		swal({
			title: "Cancel Campaign",
			text: "Are you sure you want to delete this Site?", 
			icon: "warning",
			buttons: true,
			dangerMode: true,
		}).then((willDelete) => {
			if (willDelete) {
				 swal({
			  		title: "Cancel Campaign",
					text: "Final Warning: Once deleted, you will delete the site and the zones that are tied to this site.", 
					icon: "warning",
					buttons: true,
					dangerMode: true,
				});
		  	}
		});
	});	//end of delete site 
		
	$(".deleteSite").click(function() {
		alert("Are you sure you want to delete this site");
	});	
		
	$(".deleteZone").click(function() {
		alert("Are you sure you want to delete this zone");
	});
    
	$("select").chosen({
			search_contains : true, // kwd can be anywhere
			max_shown_results : 5, // show only 5 suggestions at a time
			width: "95%",
			no_results_text: "Oops, nothing found!"
		} );

	$('.dataTableSearchOnly').DataTable({
		"oLanguage": {
		  "sSearch": "Search Table"
		}, pageLength: 10,
		responsive: true
	});	
	
  $('.chosen-toggle').each(function(index) {
    $(this).on('click', function() {
             $(this).parent().find('option').prop('selected', $(this).hasClass('select')).parent().trigger('chosen:updated');
    });
  });
	   $('.nav-click').removeClass("active");
	   $('#nav_pub_sites').addClass("active");
	   $('#nav_pub').addClass("active");
	   $('#nav_pub_menu').removeClass("collapse");
	});
</script>

@endsection
