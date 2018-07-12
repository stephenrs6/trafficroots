@extends('layouts.app') 
@section('title', 'Publisher Zone/Stats')
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
    <link href="{{ URL::asset('css/plugins/footable/footable.core.css') }}" rel="stylesheet">

<style type="text/css">
	
    #reportrange {
        width: unset;
    }
    .hide {
    	display: none;
    	    }
    .footable th:last-child .footable-sort-indicator {
            display: none;
            pointer-events: none;
        }   		    	
    @media only screen and (min-width: 769px) {
        .stats-tabs:before,
        .stats-tabs:after {
           display: none;
        }
     }
        .badge {
            font-size: 8px;
        }

                div.tableSearchOnly {
                        padding-top: 55px;

                }
                .content .ibox .ibox-content {
                        overflow: visible;
                }
</style>
@endsection
@section('js')
    <script src="{{ URL::asset('js/plugins/footable/footable.all.min.js') }}"></script>
<script src="{{ URL::asset('js/plugins/daterangepicker/daterangepicker.js') }}"></script>
@endsection
@section('content')
<div class="content">
	<div class="row">
		<div class="col-xs-12">
			<div class="panel panel-default">
				<a href="{{ URL::to('/sites') }}" class="btn btn-primary btn-xs pull-right m-t m-r">
					<span class="fa fa-arrow-circle-left"></span>&nbsp;Back to Sites</a>
				</a>
        		<h4 class="p-title">{{ $zone->description }} <small class="m-l-sm"><span id="dateRangeDisplay">{{ $startDate }}@if($endDate) - {{ $endDate }}@endif</span></small> </a></h4>
		</div>
                                                <div class="ibox-content">
                                                        <div class="row">
                                                                <div class="col-xs-12 col-md-5">
<!--
                                                        <form name="stats_form"
                                                                  id="stats_form"
                                                                  action="{{ url('/stats/pub') }}"
                                                                  method="POST">
                                                                {{ csrf_field() }}
-->
                                                                        <form name="zone_form"
                                                                  method="POST">
                                                                <label>Dates</label>
                                                                {{ csrf_field() }}
                                                                <div class="row">
                                                                        <div class="col-xs-12 form-group">
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
                                                                </div>
                                                                <div class="row">
                                                                        <div class="col-xs-12 col-md-6">
                                                                                <div class="form-group">
                                                                                        <button type="submit" class="btn btn-xs btn-primary btn-block">Submit</button>
                                                                                </div>
                                                                        </div>

                                                                </div>
                                                        </form>
                                                                </div>
                                                        </div>
                                                </div>
    		<div class="tabs-container">
				<ul class="nav nav-tabs">
					<li class="active">
						<a data-toggle="tab" href="#dates">Dates</a>
					</li>
					<li class="nav nav-tabs">
						<a data-toggle="tab" href="#countries">Countries</a>
					</li>
					<li class="nav nav-tabs">
						<a data-toggle="tab" href="#states">States</a>
					</li>
					<li class="nav nav-tabs">
						<a data-toggle="tab" href="#cities">Cities</a>
					</li>
					<li class="nav nav-tabs">
						<a data-toggle="tab" href="#platforms">Platforms</a>
					</li>
					<li class="nav nav-tabs">
						<a data-toggle="tab" href="#os">Operating Systems</a>
					</li>
					<li class="nav nav-tabs">
						<a data-toggle="tab" href="#browsers">Browsers</a>
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
											<th>Impressions</th>
											<th>Clicks</th>
											<th>CTR</th>
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
											<th>Impressions</th>
											<th>Clicks</th>
											<th>CTR</th>
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
											<th>Impressions</th>
											<th>Clicks</th>
											<th>CTR</th>
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
											<th>Impressions</th>
											<th>Clicks</th>
											<th>CTR</th>
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
											<th>Platform</th>
											<th>Impressions</th>
											<th>Clicks</th>
											<th>CTR</th>
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
											<th>Impressions</th>
											<th>Clicks</th>
											<th>CTR</th>
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
											<th>Browser</th>
											<th>Impressions</th>
											<th>Clicks</th>
											<th>CTR</th>
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
<script type="text/javascript">
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
  let text = $('#dateRangeDisplay').text().split(' - '),
	          start = text[0],
		          end = text[1],
			          initialRange = `${start} - ${end}`;
	           $('.footable').footable();
	           $('.footable').removeClass('hide');
		       

		       $('#reportrange span').html(initialRange);
		       $('input[name="daterange"]').val(initialRange);

		           $('#reportrange').daterangepicker({
			           format: 'MM/DD/YYYY',
					           dateLimit: { days: 60 },
						           showDropdowns: true,
							           showWeekNumbers: true,
								           ranges: {
									               'Today': [moment(), moment()],
											                   'Yesterday': [moment().subtract(1, 'days'), moment().subtract(1, 'days')],
													               'Last 7 Days': [moment().subtract(6, 'days'), moment()],
														                   'Last 30 Days': [moment().subtract(29, 'days'), moment()],
																               'This Month': [moment().startOf('month'), moment().endOf('month')],
																	                   'Last Month': [moment().subtract(1, 'month').startOf('month'), moment().subtract(1, 'month').endOf('month')]
																			           },
																					           opens: 'right',
																						           drops: 'down',
																							           buttonClasses: ['btn', 'btn-sm'],
																								           applyClass: 'btn-primary',
																									           cancelClass: 'btn-default',
																										           separator: ' to ',
																											           locale: {
																												               applyLabel: 'Submit',
																														                   cancelLabel: 'Cancel',
																																               fromLabel: 'From',
																																	                   toLabel: 'To',
																																			               customRangeLabel: 'Custom',
																																				                   daysOfWeek: ['Su', 'Mo', 'Tu', 'We', 'Th', 'Fr', 'Sa'],
																																						               monthNames: ['January', 'February', 'March', 'April', 'May', 'June', 'July', 'August', 'September', 'October', 'November', 'December'],
																																							                   firstDay: 1
																																									           }
				       }, (start, end, label) => {
				               console.log(start.toISOString(), end.toISOString(), label);
					               $('#reportrange span').html(start.format('MMMM D, YYYY') + ' - ' + end.format('MMMM D, YYYY'));
					               $('input[name="daterange"]').val(start.format('MMMM D, YYYY') + ' - ' + end.format('MMMM D, YYYY'));
						           });
       });
   </script>
    
    
    
@endsection
