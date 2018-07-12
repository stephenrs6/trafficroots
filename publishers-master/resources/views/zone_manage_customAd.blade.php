@extends('layouts.app')
@section('title','Zone Management')
@section('css')
@section('js')

@section('content')
<div class="content">
    <div class="row">   
        <div class="col-xs-12">
            <div class="panel panel-default">
                <a href="/zone_manage/{{ $ad->zone_handle }}" class="btn btn-primary btn-xs pull-right m-t m-r">
                    <span class="fa fa-arrow-circle-left"></span>&nbsp;Back to Zone Management</a>
                <h4 class="p-title">Manage Ad: {{ $ad->id }} - {{ $ad->description }}</h4>
			</div>
			<div class="ibox-content">
				<h2 class="text-success"><strong>Campaign Information</strong></h2>
				<div class="row">
					<div class="col-md-12">
						<div class="panel no-border">
							<div class="panel-body col-md-5">

								<table class="table">
									<thead>
										<tr></tr>
										<tr></tr>
										<tr></tr>
										<tr></tr>
										<tr></tr>
									</thead>
									<tbody>
										<tr>
											<td><strong>Date</strong></td>
											<td>
												{{ Carbon\Carbon::parse($ad->created_at)->format('m/d/Y') }} 
											</td>
										</tr>
										<tr>
											<td><strong>Campaign Name</strong></td>
											<td> {{ $ad->description }} </td>
										</tr>
										<tr>
											<td><strong>Location Type</strong></td>
											<td> {{ $location_types[$ad->location_type] }} </td>
										</tr>
										<tr>
											<td><strong>Status</strong></td>
											<td><span class="currentStatus label">{{ $status_types[$ad->status] }}</span></td>
										</tr>
									</tbody>
								</table>
							</div>
						</div>
					</div>
				</div>
				<h2 class="text-success"><strong>Edit Information</strong></h2>
				<div class="row">
					<div class="col-xs-12">
						<table class="table tablesaw tablesaw-stack" name="campaigns_table" id="campaigns_table" data-tablesaw-mode="stack">
							<thead>
								<tr>
									<th>Weight</th>
									<th>Impression Cap</th>
									<th>Frequency Capping</th>
									<th>Start Date</th>
									<th>End Date</th>
								</tr>
							</thead>
							<tbody>
								<td class="text-center"><b class=" tablesaw-cell-label">Weight</b>
									<form name="weight_form" id="weight_form" role="form" class="form-horizontal" action="/update_weight" method="POST" size="2" max="75">
										{{ csrf_field() }}
										<input type="number" id="weight" name="weight" value="{{ $ad->weight }}" size="3">
										<input type="hidden" id="ad_id" name="ad_id" value="{{ $ad->id }}">
									</form>
								</td>
								<td class="text-center"><b class=" tablesaw-cell-label">Impression Cap</b>
									<form name="impression_form" id="impression_form" role="form" class="form-horizontal" action="/update_impressionCap" method="POST">
										{{ csrf_field() }}
										<input type="number" id="impression_cap" name="impression_cap" value="{{ $ad->impression_cap }}" size="3">
										<input type="hidden" id="ad_id" name="ad_id" value="{{ $ad->id }}">
									</form>
								</td>
								<td class="text-center"><b class=" tablesaw-cell-label">Frequency Capping</b>
									<form name="frequency_form" id="frequency_form" role="form" class="form-horizontal" action="/update_frequencyAd" method="POST">
										{{ csrf_field() }}
										<select id="frequency_cap" name="frequency_cap" required>
											{!! $frequencyCapping !!}
										</select>		
										<input type="hidden" id="frequency_id" value="{{$ad->frequency_capping}}">
										<input type="hidden" id="ad_id" name="ad_id" value="{{ $ad->id }}">
									</form>
								</td>
								<td class="text-center"><b class=" tablesaw-cell-label">Start Date</b>
									<form name="start_form" id="start_form" role="form" class="form-horizontal" action="/update_start" method="POST">
										{{ csrf_field() }}
										<input type="date" id="start_date" name="start_date" value="{{ $ad->start_date }}">
										<input type="hidden" id="ad_id" name="ad_id" value="{{ $ad->id }}">
									</form>
								</td>
								<td class="text-center"><b class=" tablesaw-cell-label">End Date</b>
									<form name="end_form" id="end_form" role="form" class="form-horizontal" action="/update_end" method="POST">
										{{ csrf_field() }}
										<input type="date" id="end_date" name="end_date" value="{{ $ad->end_date }}">
										<input type="hidden" id="ad_id" name="ad_id" value="{{ $ad->id }}">
									</form>
								</td>
							</tbody>
						</table>
					</div>
				</div>
				<br>
				<form name="target_form" id="target_form" role="form" class="form-horizontal" target="#" method="POST">
					{{ csrf_field() }}
					<input type="hidden" id="ad_id" name="ad_id" value="{{ $ad->id }}">
					<h2 class="text-success"><strong>Campaign Targeting Options</strong></h2>
					<div class="col-xs-12 form-group">
						 <label>Site Targeting - Hold Ctrl to Select Multiple Themes</label>
						 <select id="themes[]" name="themes[]" class="chosen-select form-control state-control" data-placeholder="Choose a theme..." multiple>
						 {!! $themes !!}
						 </select>
					</div>
					<div class="col-xs-12 form-group">
						<label>Country / Geo Targeting - Hold Ctrl to Select Multiple Countries</label>
						<select id="countries" name="countries[]" class="chosen-select form-control" multiple>
						{!! $countries !!}
						</select>
					</div>
					<div class="col-xs-12 form-group">
						 <label>State Targeting - Hold Ctrl to Select Multiple States</label>
						 <select id="states[]" name="states[]" class="chosen-select form-control state-control" data-placeholder="Choose a state..." multiple>
						 {!! $states !!}
						 </select>
					</div>
					<div class="col-xs-12 form-group">
						 <label>County Targeting - Hold Ctrl to Select Multiple Counties</label>
						 <select id="counties" name="counties[]" class="form-control county-control" multiple>
						 {!! $counties !!}
						 </select>
					</div>
					<div class="col-xs-12 form-group">
						<label>Platform Targeting - Hold Ctrl to Select Multiple Platforms</label>
						<select name="platform_targets[]" id="platform_targets[]" class="form-control" data-placeholder="Choose platforms..." multiple>
							{!! $platforms !!}
							</select>
					</div>
					<div class="col-xs-12 form-group">
						 <label>OS Targeting - Hold Ctrl to Select Multiple Operating Systems</label>
						 <select id="operating_systems[]" name="operating_systems[]" class="form-control" data-placeholder="Choose operating systems..." multiple>
						 {!! $os_targets !!}
						 </select>
					</div>
					<div class="col-xs-12 form-group">
						 <label>Browser Targeting - Hold Ctrl to Select Multiple Browser Types</label>
						 <select id="browser_targets[]" name="browser_targets[]" class="form-control" data-placeholder="Choose browsers..." multiple>
						 {!! $browser_targets !!}
						 </select>
					</div>
					<div class="col-xs-12 form-group">
						<label>Keyword Targeting</label><small>Use commas to separate</small>
						<input name="keyword_targets" id="keyword_targets" class="form-control" type="text" value="{!! $keywords !!}">
					</div>
				</form>

				<br>
				<div class="row">
					<div class="col-xs-12">
                        <br>
                        <h2 class="text-success" id="creative_heading" style="display: inline-block;width: 100px;"><strong>Creatives</strong></h2>
                        <a class="pull-right m-t" href="/custom_creatives/{{ $ad->id }}"><button type="button" class="btn btn-primary btn-xs pull-right" id="add_creative" href="/custom_creatives/{{ $ad->id }}">Add Creative</button></a>
                    </div>
                    <div class="col-xs-12">
                        <div class="tableSearchOnly" id="creative_div">
                        @if (count($creatives))
                            <table class="table tablesaw tablesaw-stack table-striped table-hover dataTableSearchOnly dateTableFilter" name="creative_table" id="creative_table" data-tablesaw-mode="stack">
                                <thead>
                                    <tr>
                                        <th>Date</th>
										<th>Weight</th>
                                        <th>Media</th>
                                        <th>Link</th>
                                        <th>Status</th>
										<th>Options</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($creatives as $file)
                                    <tr class="creative_row" id="creative_row_{{ $file->id }}">
                                        <td class="text-center"><b class=" tablesaw-cell-label">Date</b> {{ $file->created_at->format('m/d/Y') }} </td>
										<td class="text-center"><b class=" tablesaw-cell-label">Weight</b> {{ $file->weight }} </td>
                                        <td class="text-center"><b class=" tablesaw-cell-label">Media</b>
											<a href="#" class="tr-preview" data-toggle="popover" data-html="true" data-placement="left" data-trigger="hover" title="" data-content="<img src='https://publishers.trafficroots.com/{{ $file->medias->file_location }}' width='100%' height='auto'>" id="view_media_{{ $file->id }}"><span>{{ $file->medias->file_location }}</span></a> 
										</td>
                                        <td class="text-center"><b class=" tablesaw-cell-label">Link</b><a href="{{ $file->links->url }}" target="_blank"> {{ $file->links->url }} </a></td>
                                        <td class="text-center"><b class=" tablesaw-cell-label">Status</b><span class="currentStatus label"> {{ $status_types[$file->status] }} </span></td>
										<td class="text-center"><b class=" tablesaw-cell-label">Options</b>
											<a href="{{ URL::to("/edit_custom_creative/$file->id") }}" >
												<button class="btn btn-xs btn-success alert-success">
													<span class="btn-label">
														<i class="fa fa-edit"></i>
													</span> Edit</button>
											</a>
										</td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        @else
                            <h3>No Creatives Defined</h3>
                        @endif
                        </div>
                        </div>
                    </div>
			</div>
		</div>
	</div>
</div>


   <script type="text/javascript">
       jQuery(document).ready(function ($) { 
		   $('.nav-click').removeClass("active");
		   $('#nav_pub_sites').addClass("active");
		   $('#nav_pub').addClass("active");
		   $('#nav_pub_menu').removeClass("collapse");
		   setStatus();
           toastr.options = {
             "closeButton": true,
             "debug": false,
			 "progressBar": true,
			 "preventDuplicates": false,
			 "positionClass": "toast-top-right",
			 "onclick": null,
			 "showDuration": "400",
			 "hideDuration": "1000",
			 "timeOut": "10000",
			 "extendedTimeOut": "1000",
			 "showEasing": "swing",
			 "hideEasing": "linear",
			 "showMethod": "fadeIn",
			 "hideMethod": "fadeOut"
           }
		   
		   $("#weight").change(function () {
				var url = "{{ url('/update_weight') }}";
				var mydata = $("#weight_form").serialize();
				$.post(url, mydata)
				.done(function (response) {
					toastr.success(response);
				})
				.fail(function (response) {
					toastr.error(response);
				});
			});
		   
		   	$("#impression_cap").change(function () {
				var url = "{{ url('/update_impressionCap') }}";
				var mydata = $("#impression_form").serialize();
				$.post(url, mydata)
				.done(function (response) {
					toastr.success(response);
				})
				.fail(function (response) {
					toastr.error(response);
				});
			});
		   
		   	/*frequency capping*/
		   	var frequencyid =   $('#frequency_id').val();
			$('#frequency_cap option')[frequencyid].selected = true;

			$("#frequency_cap").change(function () {
				var url = "{{ url('/update_frequencyAd') }}";
				var mydata = $("#frequency_form").serialize();
				$.post(url, mydata)
				.done(function (response) {
					toastr.success(response);
				})
				.fail(function (response) {
					toastr.error(response);
				});
			});
		   
		   $("#start_date").change(function () {
				var url = "{{ url('/update_start') }}";
				var mydata = $("#start_form").serialize();
				$.post(url, mydata)
				.done(function (response) {
					toastr.success(response);
				})
				.fail(function (response) {
					toastr.error(response);
				});
			});
		   
		   $("#end_date").change(function () {
			   var end = $("#end_date").val();
				var url = "{{ url('/update_end') }}";
				var mydata = $("#end_form").serialize();
				$.post(url, mydata)
				.done(function (response) {
					toastr.success(response);
				})
				.fail(function (response) {
					toastr.error(response);
				});
			});
		   
		   $('[data-toggle="tooltip"]').tooltip();
		   $(".form-control").change(function () {
			   	var url = "{{ url('/update_targets') }}";
            	var mydata = $("#target_form").serialize();
            	$.post(url, mydata)
                .done(function (response) {
                    toastr.success(response);
                })
                .fail(function (response) {
                    toastr.error(response);
                });
        	});
    		
		   $(".state-control").change(function () {
			   $('#counties').html('');
				var url = "{{ url('/update_counties') }}";
				var mydata = $("#target_form").serialize();
				$.post(url, mydata)
				.done(function (response) {
					$('#counties').html(response);
				})
				.fail(function (response) {
					toastr.error(response);
				});
        	});
       });
	   
	   function setStatus() {
       var currentStatus = Array.from($(".currentStatus"));
       currentStatus.forEach(function(element) {
            if (element.innerText == "Active") {
              element.classList.add("label-primary");
            } else if (element.innerText == "Declined") {
              element.classList.add("label-danger");
            } else if (element.innerText == "Disabled") {
              element.classList.add("label-default");
            } else {
              element.classList.add("label-warning");
            };
        });
   };      
   </script>
@endsection
