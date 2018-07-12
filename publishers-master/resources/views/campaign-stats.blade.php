@extends('layouts.app') 
@section('title', 'Campaign Stats')
@section('css')
<style type="text/css">
    @media only screen and (min-width: 769px) {
		.hide {
			display: none;
		}
	}
</style>
@endsection
@section('js')

@section('content')
<div class="content">
	<div class="row">
		<div class="col-xs-12">
				<div class="panel panel-default">
					<a href="{{ URL::to('/campaigns') }}" class="btn btn-primary btn-xs pull-right m-t m-r">
						<span class="fa fa-arrow-circle-left"></span>&nbsp;Back to Campaign</a>
					</a>
					<h4 class="p-title">Campaign Name: {{ $campaign->campaign_name }}
					</h4>
				</div>
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
						   href="#cities"><span class="fa fa-map-marker"></span><div>Cities</div></a>
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
								<table class="tablesaw tablesaw-stack table-striped table-hover dataTableSearchOnly dateTableFilter" data-tablesaw-mode="stack">
									<thead>
										<tr>
											<th>Date</th>
											<th>Impressions</th>
											<th>Clicks</th>
											<th>CTR</th>
											<th>Cost</th>
										</tr>
									</thead>
									<tbody>
										@foreach ($campaign->stats->groupBy('stat_date')->all() as $day)
										<tr>
											<td>{{ $day->first()->stat_date }} </td>
											<td>{{ $day->sum('impressions') }}</td>
											<td>{{ $day->sum('clicks') }}</td>
											<td>{{ ($day->sum('impressions')/1000) * $day->sum('clicks') }}%</td>
											<td>${{ number_format(
													$day->reduce(function($cost, $stat) { 
															return $cost + (($stat->impressions / 1000) * $stat->cpm); 
														}), 2
													) }}</td>
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
											<th>Impressions</th>
											<th>Clicks</th>
											<th>CTR</th>
											<th>Cost</th>
										</tr>
									</thead>
									<tbody>
										@foreach ($campaign->stats->groupBy('country_id')->all() as $country) 
												<tr>
													<td>{{ $country->first()->country->country_name }} </td>
													<td>{{ $country->sum('impressions') }}</td>
													<td>{{ $country->sum('clicks') }}</td>
													<td>{{ ($country->sum('impressions')/1000) * $country->sum('clicks') }}%</td>
													<td>${{ number_format(
														$country->reduce(function($cost, $stat) { 
															return $cost + (($stat->impressions / 1000) * $stat->cpm); 
														}), 2
													) }}</td>
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
											<th>Impressions</th>
											<th>Clicks</th>
											<th>CTR</th>
											<th>Cost</th>
										</tr>
									</thead>
									<tbody>
<!--
										@foreach ($campaign->stats->groupBy('state_code')->all() as $state) 
												<tr>
													<td>{{ $state->first()->state->state_name }} </td>
													<td>{{ $state->sum('impressions') }}</td>
													<td>{{ $state->sum('clicks') }}</td>
													<td>{{ ($state->sum('impressions')/1000) * $state->sum('clicks') }}%</td>
													<td>${{ number_format(
															$state->reduce(function($cost, $stat) { 
																return $cost + (($stat->impressions / 1000) * $stat->cpm); 
															}), 2
														) }}</td>
												</tr>
										@endforeach
-->
									</tbody>
								</table>
							</div>
						</div>
					</div>
					<div id="cities"
						class="tab-pane">
						<div class="ibox-content">
							<div class="tableSearchOnly">
								<table class="tablesaw tablesaw-stack table-striped table-hover dataTableSearchOnly dateTableFilter" data-tablesaw-mode="stack">
									<thead>
										<tr>
											<th>City</th>
											<th>Impressions</th>
											<th>Clicks</th>
											<th>CTR</th>
											<th>Cost</th>
										</tr>
									</thead>
									<tbody>
										@foreach ($campaign->stats->groupBy('city_code')->all() as $city) 
												<tr>
													<td>{{ $city->first()->city->city_name }} </td>
													<td>{{ $city->sum('impressions') }}</td>
													<td>{{ $city->sum('clicks') }}</td>
													<td>{{ ($city->sum('impressions')/1000) * $city->sum('clicks') }}%</td>
													<td>${{ number_format(
													$city->reduce(function($cost, $stat) { 
															return $cost + (($stat->impressions / 1000) * $stat->cpm); 
														}), 2
													) }}</td>
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
											<th>Impressions</th>
											<th>Clicks</th>
											<th>CTR</th>
											<th>Cost</th>
										</tr>
									</thead>
									<tbody>
										@foreach ($campaign->stats->groupBy('platform')->all() as $platform) 
												<tr>
													<td>{{ $platform->first()->platformType->platform }} </td>
													<td>{{ $platform->sum('impressions') }}</td>
													<td>{{ $platform->sum('clicks') }}</td>
													<td>{{ ($platform->sum('impressions')/1000) * $platform->sum('clicks') }}%</td>
													<td>${{ number_format(
															$platform->reduce(function($cost, $stat) { 
																return $cost + (($stat->impressions / 1000) * $stat->cpm); 
															}), 2
														) }}</td>
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
											<th>Impressions</th>
											<th>Clicks</th>
											<th>CTR</th>
											<th>Cost</th>
										</tr>
									</thead>
									<tbody>
										@foreach ($campaign->stats->groupBy('os')->all() as $os) 
												<tr>
													<td>{{ $os->first()->operatingSystem->os }} </td>
													<td>{{ $os->sum('impressions') }}</td>
													<td>{{ $os->sum('clicks') }}</td>
													<td>{{ ($os->sum('impressions')/1000) * $os->sum('clicks') }}%</td>
													<td>${{ number_format(
															$os->reduce(function($cost, $stat) { 
																return $cost + (($stat->impressions / 1000) * $stat->cpm); 
															}), 2
														) }}</td>
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
											<th>Impressions</th>
											<th>Clicks</th>
											<th>CTR</th>
											<th>Cost</th>
										</tr>
									</thead>
									<tbody>
										@foreach ($campaign->stats->groupBy('browser')->all() as $browser) 
											<tr>
													<td>{{ $browser->first()->browserType->browser }} </td>
													<td>{{ $browser->sum('impressions') }}</td>
													<td>{{ $browser->sum('clicks') }}</td>
													<td>{{ ($browser->sum('impressions')/1000) * $browser->sum('clicks') }}%</td>
													<td>${{ number_format(
															$browser->reduce(function($cost, $stat) { 
																return $cost + (($stat->impressions / 1000) * $stat->cpm); 
															}), 2
														) }}</td>
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
<script type="text/javascript">
		$('.dataTableSearchOnly').DataTable({
			"oLanguage": {
			  "sSearch": "Search Table"
			}, pageLength: 10,
			responsive: true
		});	
	</script>	
	
	
@endsection
	
	
	   
