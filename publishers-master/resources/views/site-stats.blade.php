@extends('layouts.app') 
@section('title', 'Publisher Site/Stats')
@section('css')
<style type="text/css">
    .hide {
        display: none;
    }
</style>
@endsection 

@section('js')

@section('content')
<div class="content">
	<div class="row">
		<div class="col-xs-12">
			<div class="panel panel-default">
				<a href="{{ URL::to('/sites') }}" class="btn btn-primary btn-xs pull-right m-t m-r">
					<span class="fa fa-arrow-circle-left"></span>&nbsp;Back to Sites</a>
				</a>
				<h4 class="p-title">{{ $site_name }} <small class="m-l-sm">Year to date</small></h4>
			</div>
			<div class="tabs-container">
				<ul class="nav nav-tabs">
					<li class="active">
						<a data-toggle="tab" href="#dates"><span class="fa fa-calendar"></span><div>Dates</div></a>
				  	</li>
					<li class="nav nav-tabs">
						<a data-toggle="tab" href="#countries"><span class="fa fa-globe"></span><div>Countries</div></a>
				  	</li>
				  	<li class="nav nav-tabs">
					    <a data-toggle="tab" href="#states"><span class="fa fa-location-arrow"></span><div>States</div></a>
				  	</li>
				  	<!-- <li class="nav nav-tabs">
						<a data-toggle="tab" href="#cities"><span class="fa fa-map-marker"></span><div>Cities</div></a>
				  	</li> -->
				  	<li class="nav nav-tabs">
						<a data-toggle="tab" href="#platforms"><span class="fa fa-mobile"></span><div>Platforms</div></a>
				  	</li>
				  	<li class="nav nav-tabs">
						<a data-toggle="tab" href="#os"><span class="fa fa-desktop"></span><div>Operating Systems</div></a>
				  	</li>
				  	<li class="nav nav-tabs">
						<a data-toggle="tab" href="#browsers"><span class="fa fa-laptop"></span><div>Browsers</div></a>
				  	</li>
				</ul>
        		<div class="tab-content">
					<div id="dates" class="tab-pane active">
						<div class="ibox-content">
							<div class="tableSearchOnly">
								<table class="tablesaw tablesaw-stack table-striped table-hover dataTableSearchOnly dateTableFilter" data-tablesaw-mode="stack">
									<thead>
										<tr>
											<th>Date</th>
											<th>Impressions <span class="fa fa-question-circle" data-toggle="tooltip" data-placement="top" title=" Number of times Advertising Material is served to a person visiting the Publisher’s Website"></span></th>
											<th>Clicks</th>
											<th>CTR <span class="fa fa-question-circle" data-toggle="tooltip" data-placement="top" title=" Percentage based on the total number of clicks divided by the number of impressions that an advertisement has received."></span></th>
										</tr>
									</thead>
									<tbody>
										@foreach ($stats->groupBy('stat_date') as $day)
										<tr>
											<td class="text-center"><b class=" tablesaw-cell-label">Date</b> {{ $day->first()->stat_date }} </td>
											<td class="text-center"><b class=" tablesaw-cell-label">Impressions</b> {{ $stats->where('stat_date', $day->first()->stat_date)->sum('impressions') }}</td>
											<td class="text-center"><b class=" tablesaw-cell-label">Clicks</b> {{ $stats->where('stat_date', $day->first()->stat_date)->sum('clicks') }}</td>
											<td class="text-center"><b class=" tablesaw-cell-label">CTR</b> {{ ($stats->where('stat_date', $day->first()->stat_date)->sum('impressions')/1000) * $stats->where('stat_date', $day->first()->stat_date)->sum('clicks') }}%</td>
										</tr>
										@endforeach
									</tbody>
								</table>
							</div>
						</div>
					</div>
                    
					<div id="countries" class="tab-pane">
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
										@foreach (App\Country::all() as $country) 
											@if($stats->where('country_id', $country->id)->sum('impressions'))
												<tr>
													<td class="text-center"><b class=" tablesaw-cell-label">Country</b> {{ $country->country_name }} </td>
													<td class="text-center"><b class=" tablesaw-cell-label">Impressions</b> {{ $stats->where('country_id', $country->id)->sum('impressions') }}</td>
													<td class="text-center"><b class=" tablesaw-cell-label">Clicks</b> {{ $stats->where('country_id', $country->id)->sum('clicks') }}</td>
													<td class="text-center"><b class=" tablesaw-cell-label">CTR</b> {{ ($stats->where('country_id', $country->id)->sum('impressions')/1000) * $stats->where('country_id', $country->id)->sum('clicks') }}%</td>
												</tr>
											@endif 
										@endforeach
									</tbody>
								</table>
							</div>
						</div>
					</div>
					<div id="states" class="tab-pane">
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
										@foreach (App\State::all() as $state) 
											@if($stats->where('state_code', $state->id)->sum('impressions'))
												<tr>
													<td class="text-center"><b class=" tablesaw-cell-label">State</b> {{ $state->state_name }} </td>
													<td class="text-center"><b class=" tablesaw-cell-label">Impressions</b> {{ $stats->where('state_code', $state->id)->sum('impressions') }}</td>
													<td class="text-center"><b class=" tablesaw-cell-label">Clicks</b> {{ $stats->where('state_code', $state->id)->sum('clicks') }}</td>
													<td class="text-center"><b class=" tablesaw-cell-label">CTR</b> {{ ($stats->where('state_code', $state->id)->sum('impressions')/1000) * $stats->where('state_code', $state->id)->sum('clicks') }}%</td>
												</tr>
											@endif 
										@endforeach
									</tbody>
								</table>
							</div>
						</div>
					</div>
					<div id="cities" class="tab-pane">
						<div class="ibox-content">
							<div class="tableSearchOnly">
								<table class="tablesaw tablesaw-stack table-striped table-hover dataTableSearchOnly dateTableFilter" data-tablesaw-mode="stack">
									<thead>
										<tr>
											<th>City</th>
											<th>Impressions <span class="fa fa-question-circle" data-toggle="tooltip" data-placement="top" title=" Number of times Advertising Material is served to a person visiting the Publisher’s Website"></span></th>
											<th>Clicks</th>
											<th>CTR <span class="fa fa-question-circle" data-toggle="tooltip" data-placement="top" title=" Percentage based on the total number of clicks divided by the number of impressions that an advertisement has received."></span></th>
										</tr>
									</thead>
									<tbody>
										@foreach (App\City::all() as $city) 
											@if($stats->where('city_code', $city->id)->sum('impressions'))
												<tr>
													<td class="text-center"><b class=" tablesaw-cell-label">City</b> {{ $city->city_name }} </td>
													<td class="text-center"><b class=" tablesaw-cell-label">Impressions</b> {{ $stats->where('city_code', $city->id)->sum('impressions') }}</td>
													<td class="text-center"><b class=" tablesaw-cell-label">Clicks</b> {{ $stats->where('city_code', $city->id)->sum('clicks') }}</td>
													<td class="text-center"><b class=" tablesaw-cell-label">CTR</b> {{ ($stats->where('city_code', $city->id)->sum('impressions')/1000) * $stats->where('country_id', $city->id)->sum('clicks') }}%</td>
												</tr>
											@endif 
										@endforeach
									</tbody>
								</table>
							</div>
						</div>
					</div>
					<div id="platforms" class="tab-pane">
						<div class="ibox-content">
							<div class="tableSearchOnly">
								<table class="tablesaw tablesaw-stack table-striped table-hover dataTableSearchOnly dateTableFilter" data-tablesaw-mode="stack">
									<thead>
										<tr>
											<tr>
												<th>Platform</th>
												<th>Impressions <span class="fa fa-question-circle" data-toggle="tooltip" data-placement="top" title=" Number of times Advertising Material is served to a person visiting the Publisher’s Website"></span></th>
												<th>Clicks</th>
												<th>CTR <span class="fa fa-question-circle" data-toggle="tooltip" data-placement="top" title=" Percentage based on the total number of clicks divided by the number of impressions that an advertisement has received."></span></th>
											</tr>
									</thead>
									<tbody>
										@foreach (App\Platform::all() as $platform) 
											@if($stats->where('platform', $platform->id)->sum('impressions'))
												<tr>
													<td class="text-center"><b class=" tablesaw-cell-label">Platform</b> {{ $platform->platform }} </td>
													<td class="text-center"><b class=" tablesaw-cell-label">Impressions</b> {{ $stats->where('platform', $platform->id)->sum('impressions') }}</td>
													<td class="text-center"><b class=" tablesaw-cell-label">Clicks</b> {{ $stats->where('platform', $platform->id)->sum('clicks') }}</td>
													<td class="text-center"><b class=" tablesaw-cell-label">CTR</b> {{ ($stats->where('platform', $platform->id)->sum('impressions')/1000) * $stats->where('platform', $platform->id)->sum('clicks') }}%</td>
												</tr>
											@endif 
										@endforeach
									</tbody>
								</table>
							</div>
						</div>
					</div>
					<div id="os" class="tab-pane">
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
										@foreach (App\OperatingSystem::all() as $os) 
											@if($stats->where('os', $os->id)->sum('impressions'))
												<tr>
													<td class="text-center"><b class=" tablesaw-cell-label">Operating System</b> {{ $os->os }} </td>
													<td class="text-center"><b class=" tablesaw-cell-label">Impressions</b> {{ $stats->where('os', $os->id)->sum('impressions') }}</td>
													<td class="text-center"><b class=" tablesaw-cell-label">Clicks</b> {{ $stats->where('os', $os->id)->sum('clicks') }}</td>
													<td class="text-center"><b class=" tablesaw-cell-label">CTR</b> {{ ($stats->where('os', $os->id)->sum('impressions')/1000) * $stats->where('os', $os->id)->sum('clicks') }}%</td>
												</tr>
											@endif 
										@endforeach
									</tbody>
								</table>
							</div>
						</div>
					</div>
					<div id="browsers" class="tab-pane">
						<div class="ibox-content">
							<div class="tableSearchOnly">
								<table class="tablesaw tablesaw-stack table-striped table-hover dataTableSearchOnly dateTableFilter" data-tablesaw-mode="stack">
									<thead>
										<tr>
											<tr>
												<th>Browser</th>
												<th>Impressions <span class="fa fa-question-circle" data-toggle="tooltip" data-placement="top" title=" Number of times Advertising Material is served to a person visiting the Publisher’s Website"></span></th>
												<th>Clicks</th>
												<th>CTR <span class="fa fa-question-circle" data-toggle="tooltip" data-placement="top" title=" Percentage based on the total number of clicks divided by the number of impressions that an advertisement has received."></span></th>
											</tr>
									</thead>
									<tbody>
										@foreach (App\Browser::all() as $browser) 
											@if($stats->where('browser', $browser->id)->sum('impressions'))
												<tr>
													<td class="text-center"><b class=" tablesaw-cell-label">Browser</b> {{ $browser->browser }} </td>
													<td class="text-center"><b class=" tablesaw-cell-label">Impressions</b> {{ $stats->where('browser', $browser->id)->sum('impressions') }}</td>
													<td class="text-center"><b class=" tablesaw-cell-label">Clicks</b> {{ $stats->where('browser', $browser->id)->sum('clicks') }}</td>
													<td class="text-center"><b class=" tablesaw-cell-label">CTR</b> {{ ($stats->where('browser', $browser->id)->sum('impressions')/1000) * $stats->where('browser', $browser->id)->sum('clicks') }}%</td>
												</tr>
											@endif 
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
			responsive: true
		});	
	   
       jQuery(document).ready(function ($) {
	       $('.nav-click').removeClass("active");
	       $('#nav_pub_stats').addClass("active");
	       $('#nav_pub').addClass("active");
	       $('#nav_pub_menu').removeClass("collapse");
       });
   </script>
@endsection
