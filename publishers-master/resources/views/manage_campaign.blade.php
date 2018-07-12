@extends('layouts.app')
@section('title', 'Manage Campaign')
@section('css')
<link rel="stylesheet"
      href="{{ URL::asset('css/plugins/select2/select2.min.css') }}">
<link rel="stylesheet"
      href="{{ URL::asset('css/plugins/chosen/chosen.css') }}">
@endsection

@section('js')
<script src="{{ URL::asset('js/plugins/select2/select2.full.min.js') }}"></script>
<script src="{{ URL::asset('js/plugins/chosen/chosen.jquery.js') }}"></script>
@endsection

@section('content')
    @if(Session::has('success'))
        <div class="alert alert-success">
            <h2>{{ Session::get('success') }}</h2>
        </div>
    @endif

<div class="content">
    <div class="row">   
        <div class="col-xs-12">
            <div class="panel panel-default">
                <a href="/campaigns" class="btn btn-primary btn-xs pull-right m-t m-r">
                    <span class="fa fa-arrow-circle-left"></span>&nbsp;Back to Campaigns</a>
                <h4 class="p-title">Campaign {{ $campaign->id }}</h4>
                
                <div class="ibox-content" id="bid_status_div"></div>
                <br>
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
													{{ Carbon\Carbon::parse($campaign->created_at)->format('m/d/Y') }} 
												</td>
											</tr>
											<tr>
												<td><strong>Campaign Name</strong></td>
												<td> {{ $campaign->campaign_name }} </td>
											</tr>
											<tr>
												<td><strong>Type</strong></td>
												<td>
													<span class="badge label">{{$campaign_types[$campaign->campaign_type]}}</span>
												</td>
											</tr>
											<tr>
												<td><strong>Category</strong></td>
												<td> {{ $categories[$campaign->campaign_category] }} </td>
											</tr>
											<tr>
												<td><strong>Location Type</strong></td>
												<td> {{ $location_types[$campaign->location_type] }} </td>
											</tr>
											<tr>
												<td><strong>Status</strong></td>
												<td><span class="currentStatus label">{{ $status_types[$campaign->status] }}</span></td>
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
										<th>Bid</th>
										<th>Daily Budget</th>
										<th>Frequency Capping</th>
										<th>Options</th>
									</tr>
								</thead>
								<tbody>
									<tr class="camp_row" id="camp_row_{{ $campaign->id }}">
										<td class="text-center"><b class=" tablesaw-cell-label">Bid</b>
											<form name="bid_form" id="bid_form" role="form" class="form-horizontal" action="/update_bid" method="POST">
											{{ csrf_field() }}
											<input type="number" id="bid" name="bid" value="{{ $campaign->bid }}" size="5">
											<input type="hidden" id="camp_id" name="camp_id" value="{{ $campaign->id }}">
											</form>
										</td>
										<td class="text-center"><b class=" tablesaw-cell-label">Daily Budget</b>
											<form name="budget_form" id="budget_form" role="form" class="form-horizontal" action="/update_budget" method="POST">
											{{ csrf_field() }}
											<input type="number" id="daily_budget" name="daily_budget" value="{{ $campaign->daily_budget }}" size="5">
											<input type="hidden" id="camp_id" name="camp_id" value="{{ $campaign->id }}">
											</form>
										</td>
										<td class="text-center"><b class=" tablesaw-cell-label">Frequency Capping</b>
											<form name="frequency_form" id="frequency_form" role="form" class="form-horizontal" action="/update_frequency" method="POST">
											{{ csrf_field() }}
											<select id="frequency_cap" name="frequency_cap" required>
												{!! $frequencyCapping !!}
											</select>		
											<input type="hidden" id="frequency_id" value="{{$campaign->frequency_capping}}">
											<input type="hidden" id="camp_id" name="camp_id" value="{{ $campaign->id }}">
											</form>
										</td>
										<td class="text-center"><b class=" tablesaw-cell-label">Options</b>
											@if( $campaign->status == 3)
											&nbsp;<a href="#" data-toggle="tooltip" title="Start this Campaign" class="camp-start" id="camp_start_{{ $campaign->id }}"><i class="fa fa-play" aria-hidden="true"></i></a>
											@endif
											@if( $campaign->status == 1)
											&nbsp;<a href="#" data-toggle="tooltip" title="Pause this Campaign" class="camp-stop" id="camp_stop_{{ $campaign->id }}"><i class="fa fa-pause" aria-hidden="true"></i></a>
											@else 
											&nbsp;<i class="fa fa-pause" aria-hidden="true"></i>
											@endif
											&nbsp;&nbsp;&nbsp;
											<a href="{{ url("stats/campaign/$campaign->id") }}" >
												<button class="campaign-stats btn btn-xs btn-warning alert-info">
													<span class="btn-label">
														<i class="fa fa-line-chart"></i>
													</span>&nbsp; Stats&nbsp;&nbsp;
												</button>
											</a>
										</td>
										<!--
										 <td class="text-center">
											 <b class=" tablesaw-cell-label">Preview</b> 
												 <i class="fa fa-camera" aria-hidden="true"></a></i> 
										</td>
										-->
									</tr>
								</tbody>
							</table>
						</div>
					</div>
                    <br>
                    <form name="target_form" id="target_form" role="form" class="form-horizontal" target="#" method="POST">
                            {{ csrf_field() }}
                            <input type="hidden" id="campaign_id" name="campaign_id" value="{{ $campaign->id }}">
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
                    <div class="row">
                    <div class="col-xs-12">
                        <br>
                        <h2 class="text-success" id="creative_heading" style="display: inline-block;width: 100px;"><strong>Creatives</strong></h2>
                        <a class="pull-right m-t" href="/creatives/{{ $campaign->id }}"><button type="button" class="btn btn-primary btn-xs pull-right" id="add_creative" href="/creatives/{{ $campaign->id }}">Add Creative</button></a>
                    </div>
                    <div class="col-xs-12">
                        <div class="tableSearchOnly" id="creative_div">
                        @if (count($creatives))
                            <table class="table tablesaw tablesaw-stack table-striped table-hover dataTableSearchOnly dateTableFilter" name="creative_table" id="creative_table" data-tablesaw-mode="stack">
                                <thead>
                                    <tr>
                                        <th>Date</th>
                                        <th>Creative Name</th>
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
                                        <td class="text-center"><b class=" tablesaw-cell-label">Creative Name</b>{{ $file->description }} </td>
                                        <td class="text-center"><b class=" tablesaw-cell-label">Media</b>
											<a href="#" class="tr-preview" data-toggle="popover" data-html="true" data-placement="left" data-trigger="hover" title="" data-content="<img src='https://publishers.trafficroots.com/{{ $file->medias->file_location }}' width='100%' height='auto'>" id="view_media_{{ $file->id }}"><span>{{ $file->medias->media_name }}</span></a> 
										</td>
                                        <td class="text-center"><b class=" tablesaw-cell-label">Link</b><a href="{{ $file->links->url }}" target="_blank"> {{ $file->links->link_name }} </a></td>
                                        <td class="text-center"><b class=" tablesaw-cell-label">Status</b><span class="currentStatus label"> {{ $status_types[$file->status] }} </span></td>
										<td class="text-center"><b class=" tablesaw-cell-label">Options</b>
											<a href="{{ URL::to("/edit_creative/$file->id") }}" >
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
</div>
<script type="text/javascript">
jQuery(document).ready(function ($) {
    $('.dataTableSearchOnly').DataTable({
        "oLanguage": {
          "sSearch": "Search Table"
        }, pageLength: 10,
        responsive: true
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
    $("#bid").change(function () {
            var url = "{{ url('/update_bid') }}";
            var mydata = $("#bid_form").serialize();
            $.post(url, mydata)
                .done(function (response) {
            toastr.success(response.result);
            if(response.bid_class == 'success') toastr.info(response.bid_range, "Bid Status");
                        if(response.bid_class == 'info') toastr.info(response.bid_range, "Bid Status");
                        if(response.bid_class == 'warning') toastr.warning(response.bid_range, "Bid Status");
                        if(response.bid_class == 'danger') toastr.error(response.bid_range, "Bid Status");

                })
                .fail(function (response) {
                    toastr.error(response.result);
                });
    });
        $("#daily_budget").change(function () {
        var url = "{{ url('/update_budget') }}";
        var mydata = $("#budget_form").serialize();
        $.post(url, mydata)
        .done(function (response) {
                    toastr.success(response);
                })
                .fail(function (response) {
            toastr.error(response);
                });
        });
	
		var bidType = $(".badge").text();
		if (bidType == "CPM"){
			$(".badge").addClass('label-success');
		} else {
			$(".badge").addClass('label-danger');
		}
	
		/*frequency capping*/
		var frequencyid =   $('#frequency_id').val();
		$('#frequency_cap option')[frequencyid].selected = true;
	
		$("#frequency_cap").change(function () {
			var url = "{{ url('/update_frequency') }}";
			var mydata = $("#frequency_form").serialize();
			$.post(url, mydata)
			.done(function (response) {
						toastr.success(response);
					})
					.fail(function (response) {
				toastr.error(response);
					});
		});
	
		// Prevent 'enter' key press from displaying json on post data	
        $("#bid, #daily_budget").keypress(function(event){	
        	if (event.keyCode === 10 || event.keyCode === 13)	
				event.preventDefault();	
         });
	
        $('.camp-start').click(function() {
            if(confirm('Activate this campaign?')){
                var str =  $(this).attr('id');
                var res = str.split("_");
                var url = '/campaign/start/' + res[2];
                $.get(url)
                    .done(function (response) {
                        toastr.success(response, function(){
                          setTimeout(function(){ window.location.reload(); }, 3000);
                        });
                    })
                    .fail(function (response) {
                        toastr.error(response);
                    });
            }else{
                return false;
            }

        });
        $('.camp-stop').click(function() {
            if(confirm('Pause this campaign?')){
                var str =  $(this).attr('id');
                var res = str.split("_");
                var url = '/campaign/pause/' + res[2];
                $.get(url)
                    .done(function (response) {
                        toastr.success(response, function(){
                          setTimeout(function(){ window.location.reload(); }, 3000);
                        });
                    })
                    .fail(function (response) {
                        toastr.error(response);
                    });
            }else{
                return false;
            }

        });
    });
</script>
   <script type="text/javascript">
       jQuery(document).ready(function ($) {
           $('.nav-click').removeClass("active");
           $('#nav_buyer_campaigns').addClass("active");
           $('#nav_buyer').addClass("active");
           $('#nav_buyer_menu').removeClass("collapse");
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
           @if($bid_class == 'success')
               toastr.info("{{ $bid_range }}", "Bid Status");
           @endif
           @if($bid_class == 'info')
               toastr.info("{{ $bid_range }}", "Bid Status");
           @endif
           @if($bid_class == 'warning')
               toastr.warning("{{ $bid_range }}", "Bid Status");
           @endif
           @if($bid_class == 'danger')
               toastr.error("{{ $bid_range }}", "Bid Status");
           @endif
       });
           
	@if(session()->has('creative_updated'))
		toastr.success("{{ Session::get('creative_updated') }}");
	@endif		   
		   
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
