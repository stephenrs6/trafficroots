@extends('layouts.app') 
@section('title','Publisher Stats')
@section('css')
<link rel="stylesheet"
      href="{{ URL::asset('css/plugins/daterangepicker/daterangepicker.css') }}">
<link rel="stylesheet"
      href="{{ URL::asset('css/plugins/select2/select2.min.css') }}">
<link rel="stylesheet"
      href="{{ URL::asset('css/plugins/chosen/chosen.css') }}">
<link rel="stylesheet"
      href="{{ URL::asset('css/custom.css') }}">
<link rel="stylesheet"
      href="{{ URL::asset('css/plugins/tablesaw/tablesaw.css') }}">
<style type="text/css">
	
#reportrange {
    width: unset;
}
	
.chosen-select {
	width: 100%;
}

.hide {
    display: none;
}
	
div#sites_chosen {
    width: 100% !important;
    display: block;
}
	
@media only screen and (min-width: 769px) {
    .stats-tabs:before,
    .stats-tabs:after {
        display: none;
    }
}
</style>
@endsection 

@section('js')
<script src="{{ URL::asset('js/plugins/footable/footable.all.min.js') }}"></script>
{{-- <script src="{{ URL::asset('js/plugins/fullcalendar/moment.min.js') }}"></script> --}}
<script src="{{ URL::asset('js/plugins/daterangepicker/daterangepicker.js') }}"></script>
<script src="{{ URL::asset('js/plugins/select2/select2.full.min.js') }}"></script>
<script src="{{ URL::asset('js/plugins/chosen/chosen.jquery.js') }}"></script>
@endsection 

@section('content')
<div class="content">
	<div class="row">
		<div class="col-xs-12">
			<div class="ibox-title" style="display:none;">
				<h5>
					Stats for: <span id="dateRangeDisplay">{{ Carbon\Carbon::parse($startDate)->format('Y-m-d') }}@if($endDate) - {{ Carbon\Carbon::parse($endDate)->format('Y-m-d') }}@endif</span>
				</h5>
			</div>
			<div class="row">
				<div class="col-md-12">
					<div class="panel panel-default">
						<h4 class="p-title">Filter</h4>
						<div class="ibox-content">
							<div class="row">
								<div class="col-xs-12 col-md-8">
									<form name="stats_form"
										  id="stats_form"
										  action="{{ url('/stats/pub') }}"
										  method="POST">
									{{ csrf_field() }}
									<div class="row">
										<div class="col-xs-12 col-md-6 form-group">
											<label>Dates</label>
											<input hidden="true"
												   type="text"
												   name="daterange" />
											<div id="reportrange"
												 class="form-control">
												<i class="fa fa-calendar" style="float: right;"></i>
												<span></span>
											</div>
										<label class="error hide"
											   for="dates"></label>
										</div>
										<div class="col-xs-12 col-md-6 form-group">
											<label>Sites</label>
											<select name="sites[]" 
														id="sites"
														data-placeholder="Choose sites..."
														class="chosen-select"
														multiple
														tabindex="3">
													<option value="">Select</option>
													@foreach(Auth::User()->sites as $site)
														<option value="{{ $site->id }}"
														@if(is_array($filter_sites) && in_array($site->id, $filter_sites))
														 selected
														@endif
														>{{ $site->site_name }}</option>
													@endforeach
												</select>
											<label class="error hide"
												   for="sites"></label>
										</div>
									</div>
									<div class="row">
										<div class="col-xs-12 col-md-6">
											<div class="form-group">
												<button type="submit" class="btn btn-primary btn-block">Submit</button>
											</div>
										</div>

										<div class="col-xs-12 col-md-6">
											<div class="form-group">
												<button type="submit" class="btn btn-danger 	btn-block" id="resetFilter">Reset Filter</button>
											</div>
										</div>
									</div>
								</form>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
			<div class="row">
			<div class="col-md-12">
					<div class="tabs-container">
						<ul class="nav nav-tabs stats-tab">
							<li class="active">
								<a data-toggle="tab"
								   href="#dates"><span class="fa fa-calendar"></span><div>Dates</div></a>
							</li>
							<li class="nav nav-tabs">
								<a data-toggle="tab"
								   href="#countries"><span class="fa fa-globe"></span><div>Countries</div></a>
							</li>
							<li class="nav nav-tabs">
								<a data-toggle="tab"
								   href="#states"><span class="fa fa-location-arrow"></span><div>States</div></a>
							</li>
							<li class="nav nav-tabs">
								<a data-toggle="tab"
								   href="#platforms"><span class="fa fa-mobile"></span><div>Platforms</div></a>
							</li>
							<li class="nav nav-tabs">
								<a data-toggle="tab"
								   href="#os"><span class="fa fa-desktop"></span><div>Operating Systems</div></a>
							</li>
							<li class="nav nav-tabs">
								<a data-toggle="tab"
								   href="#browsers"><span class="fa fa-laptop"></span><div>Browsers</div></a>
							</li>
						</ul>
						<div class="tab-content">
							<div id="dates"
								 class="tab-pane active">
								 
								<div class="ibox-content">
									<div class="tableSearchOnly">
										<table class="tablesaw tablesaw-stack table-striped table-hover dataTableDateSort dateTableFilter" data-tablesaw-mode="stack">
										<thead>
											<tr>
												<th>Date</th>
												<th>Impressions <span class="fa fa-question-circle" data-toggle="tooltip" data-placement="top" title=" Number of times Advertising Material is served to a person visiting the Publisher’s Website"></span></th>
												<th>Clicks</th>
												<th>CTR <span class="fa fa-question-circle" data-toggle="tooltip" data-placement="top" title=" Percentage based on the total number of clicks divided by the number of impressions that an advertisement has received."></span></th>
											</tr>
										</thead>
										<tbody>
										@foreach($dates as $day)
											<tr>
												<td class="text-center"><b class=" tablesaw-cell-label">Date</b>
													{{ $day->stat_date}} </td>
												<td class="text-center" data-order="{{ $day->impressions }}"><b class=" tablesaw-cell-label">Impressions</b>{{ $day->impressions }}</td>
												<td class="text-center" data-order="{{ $day->clicks }}"><b class=" tablesaw-cell-label">Clicks</b>{{ $day->clicks }}</td>
												<td class="text-center"><b class=" tablesaw-cell-label">CTR</b>{{ $day->impressions ? round($day->clicks / $day->impressions, 5) : 0 }}%</td>
											</tr>
										@endforeach
										</tbody>
									</table>
									</div>
								</div>
																 
							</div>
							<div id="countries"
								 class="tab-pane">
								 <div class="ibox-content">
									<div class="tableSearchOnly">
										<table class="tablesaw tablesaw-stack table-striped table-hover dataTableSearchOnly dateTableFilter" data-tablesaw-mode="stack">
											<thead>
												<tr>
													<th>Country</th>
													<th>Impressions <span class="fa fa-question-circle" data-toggle="tooltip" data-placement="top" title=" Number of times Advertising Material is served to a person visiting the Publisher’s Website"></span></th>
													<th>Clicks</th>
													<th>CTR <span class="fa fa-question-circle" data-toggle="tooltip" data-placement="top" title=" Percentage based on the total number of clicks divided by the number of impressions that an advertisement has received."></span></th>
												</tr>
											</thead>
											<tbody>
												@foreach($country_stats as $country) 
													
														<tr>
															<td class="text-center"><b class=" tablesaw-cell-label">Country</b> {{ $country->country_name }} </td>
															<td class="text-center" data-order="{{ $country->impressions }}"><b class=" tablesaw-cell-label">Impressions</b>{{ $country->impressions }}</td>
															<td class="text-center" data-order="{{ $country->clicks }}"><b class=" tablesaw-cell-label">Clicks</b>{{ $country->clicks }}</td>
															<td class="text-center"><b class=" tablesaw-cell-label">CTR</b>{{ $country->impressions ? round($country->clicks / $country->impressions,5) : 0 }}%</td>
														</tr>
													
												@endforeach
											</tbody>
										</table>
									</div>
								</div>
							</div>
							<div id="states"
								 class="tab-pane">
								 <div class="ibox-content">
									<div class="tableSearchOnly">
										<table class="tablesaw tablesaw-stack table-striped table-hover dataTableSearchOnly dateTableFilter" data-tablesaw-mode="stack">
											<thead>
												<tr>
													<th>State</th>
													<th>Impressions <span class="fa fa-question-circle" data-toggle="tooltip" data-placement="top" title=" Number of times Advertising Material is served to a person visiting the Publisher’s Website"></span></th>
													<th>Clicks</th>
													<th>CTR <span class="fa fa-question-circle" data-toggle="tooltip" data-placement="top" title=" Percentage based on the total number of clicks divided by the number of impressions that an advertisement has received."></span></th>
												</tr>
											</thead>
											<tbody>
												@foreach($state_stats as $state) 
													
														<tr>
															<td class="text-center"><b class=" tablesaw-cell-label">State</b> {{ $state->state_name }}, {{ $state->country_short}} </td>
															<td class="text-center" data-order="{{ $state->impressions }}"><b class=" tablesaw-cell-label">Impressions</b>{{ $state->impressions }}</td>
															<td class="text-center" data-order="{{ $state->clicks }}"><b class=" tablesaw-cell-label">Clicks</b>{{ $state->clicks }}</td>
															<td class="text-center"><b class=" tablesaw-cell-label">CTR</b>{{ $state->impressions ? round($state->clicks / $state->impressions,5) : 0 }}%</td>
														</tr>
													
												@endforeach
											</tbody>
										</table>
									</div>
								</div>
							</div>
							<div id="platforms"
								 class="tab-pane">
								 <div class="ibox-content">
									<div class="tableSearchOnly">
										<table class="tablesaw tablesaw-stack table-striped table-hover dataTableSearchOnly dateTableFilter" data-tablesaw-mode="stack">
											<thead>
												<tr>
													<th>Platform</th>
													<th>Impressions <span class="fa fa-question-circle" data-toggle="tooltip" data-placement="top" title=" Number of times Advertising Material is served to a person visiting the Publisher’s Website"></span></th>
													<th>Clicks</th>
													<th>CTR <span class="fa fa-question-circle" data-toggle="tooltip" data-placement="top" title=" Percentage based on the total number of clicks divided by the number of impressions that an advertisement has received."></span></th>
												</tr>
											</thead>
											<tbody>
												
													@foreach($platform_stats as $pstat)
														<tr>
															<td class="text-center"><b class=" tablesaw-cell-label">Platform</b> {{ $pstat->platform }} </td>
															<td class="text-center" data-order="{{ $pstat->impressions }}"><b class=" tablesaw-cell-label">Impressions</b>{{ $pstat->impressions}}</td>
															<td class="text-center" data-order="{{ $pstat->clicks }}"><b class=" tablesaw-cell-label">Clicks</b>{{ $pstat->clicks }}</td>
															<td class="text-center"><b class=" tablesaw-cell-label">CTR</b>{{ $pstat->impressions ? round($pstat->clicks / $pstat->impressions,5) : 0 }}%</td>
														</tr>
													@endforeach
											
											</tbody>
										</table>
									</div>
								</div>
							</div>
							<div id="os"
								 class="tab-pane">
								 <div class="ibox-content">
									<div class="tableSearchOnly">
										<table class="tablesaw tablesaw-stack table-striped table-hover dataTableSearchOnly dateTableFilter" data-tablesaw-mode="stack">
											<thead>
												<tr>
													<th>Operating System</th>
													<th>Impressions <span class="fa fa-question-circle" data-toggle="tooltip" data-placement="top" title=" Number of times Advertising Material is served to a person visiting the Publisher’s Website"></span></th>
													<th>Clicks</th>
													<th>CTR <span class="fa fa-question-circle" data-toggle="tooltip" data-placement="top" title=" Percentage based on the total number of clicks divided by the number of impressions that an advertisement has received."></span></th>
												</tr>
											</thead>
											<tbody>
												@foreach ($os_stats as $os) 
													
														<tr>
															<td class="text-center"><b class=" tablesaw-cell-label">Operating System</b> {{ $os->os }} </td>
															<td class="text-center" data-order="{{ $os->impressions }}"><b class=" tablesaw-cell-label">Impressions</b> {{ $os->impressions }}</td>
															<td class="text-center" data-order="{{ $os->clicks }}"><b class=" tablesaw-cell-label">Clicks</b> {{ $os->clicks }}</td>
															<td class="text-center"><b class=" tablesaw-cell-label">CTR</b> {{ $os->impressions ? round($os->clicks / $os->impressions, 5) : 0 }}%</td>
														</tr>
													
												@endforeach
											</tbody>
										</table>
									</div>
								</div>
							</div>
							<div id="browsers"
								 class="tab-pane">
								 <div class="ibox-content">
									<div class="tableSearchOnly">
										<table class="tablesaw tablesaw-stack table-striped table-hover dataTableSearchOnly dateTableFilter" data-tablesaw-mode="stack">
											<thead>
												<tr>
													<th>Browser</th>
													<th>Impressions <span class="fa fa-question-circle" data-toggle="tooltip" data-placement="top" title=" Number of times Advertising Material is served to a person visiting the Publisher’s Website"></span></th>
													<th>Clicks</th>
													<th>CTR <span class="fa fa-question-circle" data-toggle="tooltip" data-placement="top" title=" Percentage based on the total number of clicks divided by the number of impressions that an advertisement has received."></span></th>
												</tr>
											</thead>
											<tbody>
												@foreach ($browser_stats as $browser) 
													
														<tr>
															<td class="text-center"><b class=" tablesaw-cell-label">Browser</b> {{ $browser->browser }} </td>
															<td class="text-center" data-order="{{ $browser->impressions }}"><b class=" tablesaw-cell-label">Impressions</b> {{ $browser->impressions }}</td>
															<td class="text-center" data-order="{{ $browser->clicks }}"><b class=" tablesaw-cell-label">Clicks</b> {{ $browser->clicks }}</td>
															<td class="text-center"><b class=" tablesaw-cell-label">CTR</b> {{ $browser->impressions ? round($browser->clicks / $browser->impressions,5) : 0 }}%</td>
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
			</div>
		</div>
	</div>
</div>

<script src="{{ URL::asset('js/plugins/flot/jquery.flot.js') }}"></script>
<script src="{{ URL::asset('js/plugins/flot/jquery.flot.tooltip.min.js') }}"></script>

   <script type="text/javascript">
   $(document).ready(function(){
	   $('[data-toggle="tooltip"]').tooltip();
	});
		$('.dataTableSearchOnly').DataTable({
			"oLanguage": {
			  "sSearch": "Search Table"
			}, pageLength: 10,
			responsive: true,
			"order": [[ 1, 'desc' ]]			
		});	
		$('.dataTableDateSort').DataTable({
			"oLanguage": {
			  "sSearch": "Search Table"
			}, pageLength: 10,
			responsive: true,
			"order": [[ 0, 'asc' ]]			
		});		   
       jQuery(document).ready(function ($) {
	       $('.nav-click').removeClass("active");
	       $('#nav_pub_stats').addClass("active");
	       $('#nav_pub').addClass("active");
	       $('#nav_pub_menu').removeClass("collapse");
       });
   </script>
@endsection
