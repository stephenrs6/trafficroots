@extends('layouts.app') 
@section('css')
@section('content')


@if($view_type == 1 || $view_type == 3)
@section('title', 'Publisher Dashboard') 
@else
@section('title', 'Advertiser Dashboard')
@endif
{{-- @if(Session::has('success'))
<div class="alert alert-success">
    <h2>{{ Session::get('success') }}</h2>
</div>
@endif  --}}
@if($view_type == 1 || $view_type == 3)
<div class="content">
    <div class="row">
        <div class="col-lg-12">
											
			<div class="row">
				<div class="col-xs-12">
					<h4>Month to Date</h4>
				</div>
				<div class="col-sm-2 widget-boxs">
					<div class="ibox float-e-margins">
						<div class="ibox-title purple-bg">
							<span class="fa fa-eye"></span>
							<span>Impressions</span>
						</div>
						<div class="ibox-content">
						  <span class="totalStat">
							  {{ number_format($pub_data['impressions_this_month']) }}
							</span>
						</div>
					</div>
				</div>
				<div class="col-sm-2 widget-boxs">
					<div class="ibox float-e-margins">
						<div class="ibox-title yellow-bg">
							<span class="fa fa-location-arrow"></span>
							<span>Clicks</span>
						</div>
						<div class="ibox-content">
							<span class="totalStat">
								{{ number_format($pub_data['clicks_this_month']) }}
							</span>
						</div>
					</div>
				</div>
				<div class="col-sm-2 widget-boxs">
					<div class="ibox float-e-margins">
						<div class="ibox-title navy-bg">
							<span class="fa fa-money"></span>
							<span>Earnings</span>
						</div>
						<div class="ibox-content">
							<span class="totalStat">
								{{ money_format('%(#10n',$pub_data['earned_this_month']) }}
							</span>
						</div>
					</div>
				</div>
				<div class="col-sm-2 widget-boxs">
					<div class="ibox float-e-margins">
						<div class="ibox-title blue-bg">
							<span class="fa fa-bar-chart-o"></span>
							<span>CPM</span>
						</div>
						<div class="ibox-content">
							<span class="totalStat">{{ money_format('%(#10n',$pub_data['cpm_this_month']) }}</span>
						</div>
					</div>
				</div>
				<div class="col-sm-2 widget-boxs">
					<div class="ibox float-e-margins">
						<div class="ibox-title red-bg">
							<span class="fa fa-hand-o-up"></span>
							<span>CPC</span>
						</div>
						<div class="ibox-content">
							<span class="totalStat">
								@if($pub_data['clicks_this_month'])
							    	{{ money_format('%(#10n',($pub_data['earned_this_month'] / $pub_data['clicks_this_month'])) }}
								@else
							   		$ 0.00
								@endif
							</span>
						</div>
					</div>
				</div>
			</div>

			<div class="row">
				<div class="col-lg-4">
					<div class="ibox float-e-margins">
						<div class="ibox-title">
							<span class="label label-success pull-right">{{ date('F jS', strtotime('yesterday')) }}</span>
							<h5>Yesterday</h5>
						</div>
						<div class="ibox-content">
							<div class="stat-percent font-bold text-success pull-right">{{ number_format($pub_data['impressions_yesterday']) }}</div>
							<small>Impressions</small><br />
							<div class="stat-percent font-bold text-success pull-right">{{ number_format($pub_data['clicks_yesterday']) }}</div>
							<small>Clicks</small><br />                
							<div class="stat-percent font-bold text-success pull-right">{{ money_format('%(#10n',$pub_data['earned_yesterday']) }}</div>
							<small>Earnings</small><br />
							<div class="stat-percent font-bold text-success">{{ money_format('%(#10n',$pub_data['cpm_yesterday']) }}</div>
							<small>CPM</small>                
						</div>
					</div>
				</div>
				<div class="col-lg-4">
					<div class="ibox float-e-margins">
						<div class="ibox-title">
							<span class="label label-success pull-right">{{ date('F', strtotime('last month')) }}</span>
							<h5>Last Month</h5>
						</div>
						<div class="ibox-content">
							<div class="stat-percent font-bold text-success pull-right">{{ number_format($pub_data['impressions_last_month']) }}</div>
							<small>Impressions</small><br />
							<div class="stat-percent font-bold text-success pull-right">{{ number_format($pub_data['clicks_last_month']) }}</div>
							<small>Clicks</small><br />                
							<div class="stat-percent font-bold text-success pull-right">{{ money_format('%(#10n',$pub_data['earned_last_month']) }}</div>
							<small>Earnings</small><br />
							<div class="stat-percent font-bold text-success pull-right">{{ money_format('%(#10n',$pub_data['cpm_last_month']) }}</div>
							<small>CPM</small>  
						</div>
					</div>
				</div>
				<div class="col-lg-4">
					<div class="ibox float-e-margins">
						<div class="ibox-title">
							<span class="label label-success pull-right">{{ date('Y') }}</span>
							<h5>This Year</h5>
						</div>
						<div class="ibox-content">
							<div class="stat-percent font-bold text-success">{{ number_format($pub_data['impressions_this_year']) }}</div>
							<small>Impressions</small><br />
							<div class="stat-percent font-bold text-success">{{ number_format($pub_data['clicks_this_year']) }}</div>
							<small>Clicks</small><br />                
							<div class="stat-percent font-bold text-success">{{ money_format('%(#10n',$pub_data['earned_this_year']) }}</div>
							<small>Earnings</small><br />
							<div class="stat-percent font-bold text-success">{{ money_format('%(#10n',$pub_data['cpm_this_year']) }}</div>
							<small>CPM</small>  
						</div>
					</div>
				</div>
			</div>
			
			<div class="row">
				<div class="col-lg-12">
					<div class="ibox float-e-margins">
						<div class="ibox-title">
							<h5>Daily Stats</h5>
						</div>
						<div class="ibox-content">
								<div class="row">
									<div class="col-lg-9">            
										<div class="flot-chart">
											<div class="flot-chart-content" id="flot-line-chart-multi"></div>
										</div>
									</div>
									<div class="col-lg-3" id="pub-stat-list">
										<ul class="stat-list">
											<li id="pub-impressions">
												<h2 class="no-margins"> {{ number_format($pub_data['impressions_this_month']) }} </h2>
												<small>Impressions</small>
												<div class="progress progress-mini">
													<div style="width: 100%;" class="progress-bar"></div>
												</div>
											</li>
											<li id="pub-earnings">
												<h2 class="no-margins ">{{ money_format('%(#10n',$pub_data['earned_this_month']) }} </h2>
												<small>Earnings</small>
												<div class="progress progress-mini">
													<div style="width: 100%;" class="progress-bar"></div>
												</div>
											</li>
											<li id="pub-cpm">
												<h2 class="no-margins ">{{ money_format('%(#10n',$pub_data['cpm_this_month']) }} </h2>
												<small>Cost Per Mili</small>
												<div class="progress progress-mini">
													<div style="width: 100%;" class="progress-bar"></div>
												</div>
											</li>
										</ul>
									</div>
								</div>
							</div>
					</div>
				</div>
			</div>
			<div class="row">
				<div class="col-lg-12">
					<div class="ibox float-e-margins xs-ibox-title">
						<div class="panel panel-default">
							<div class="col-xs-12 col-md-6">
								<h4 class="p-title">Active Zones</h4>
							</div>
<!--
							<div class="col-xs-12 col-md-6 text-right" style="padding: 10px 25px;">
									<label>Update Status:</label>
									<button type="button" class="btn btn-success btn-sm">Approve</button>
									<button type="button" class="btn btn-danger btn-sm">Decline</button>
									<button type="button" class="btn btn-info btn-sm">Restore</button>
								</div>
-->
							<div class="ibox-content">
								<div class="tableSearchOnly">
									<table class="tablesaw tablesaw-stack table-striped table-hover dataTableSearchOnly dateTableFilter" data-tablesaw-mode="stack">
									<thead>
										<tr>
											<th>Site</th>
											<th>Location</th>
											<th>Impressions Today</th>
											<th>Clicks Today</th>
											<th>Earnings</th>
											<th>CPM</th>
											<th>CPC</th>
											<th>CTR</th>
											<th>Preview</th>
										</tr>
									</thead>
									<tbody>
										@foreach($pub_data['active_zones'] as $zone)
										<tr>
											<td class="text-center"><b class=" tablesaw-cell-label">Site</b>{{ $zone->site_name }}</td>
											<td class="text-center"><b class=" tablesaw-cell-label">Location</b>{{ $zone->description }}</td>
											<td class="text-center"><b class=" tablesaw-cell-label">Impressions</b>{{ $zone->impressions }}</td>
											<td class="text-center"><b class=" tablesaw-cell-label">Clicks</b>{{ $zone->clicks }} </td>
											<td class="text-center"><b class=" tablesaw-cell-label">Earnings</b>{{ money_format('%(#10n',$zone->earned) }}</td>
											<td class="text-center"><b class=" tablesaw-cell-label">CPM</b>
												{{ money_format('%(#10n', round(($zone->earned / ($zone->impressions / 1000)),2)) }} 
											</td>
											<td class="text-center"><b class=" tablesaw-cell-label">CPC</b>
												@if($zone->clicks)
												{{ money_format('%(#10n',round($zone->earned / $zone->clicks, 2)) }}
												@else
												$ 0.00
												@endif
											</td>
											<td class="text-center"><b class=" tablesaw-cell-label">CTR</b>
												@if($zone->clicks)
												{{ money_format('%(#10n',round($zone->clicks / $zone->impressions, 2)) }}
												@else
												$ 0.00
												@endif
											</td>
											<td class="text-center"><b class=" tablesaw-cell-label">Preview Campaigns</b>
												<a href="/zone_preview/{{ $zone->handle }}">
												<button class="btn btn-xs alert-info"><span class="btn-label"><i class="fa fa-map"></i></span>Preview</button>
												</a>
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
		</div>
	</div>
</div>
@endsection
@section('js')
<script type="text/javascript">
$( document ).ready(function() {
	//Check and uncheck records
	$(".checkAll").click(function(event) {   
		if(this.checked) {
			// Iterate each checkbox
			$(':checkbox').each(function() {
				this.checked = true;                        
			});
		} else {
			$(':checkbox').each(function() {
				this.checked = false;                        
			});
		}
	});
	
	$('[data-toggle="tooltip"]').tooltip();
		
	var currentStatus = $(".currentStatus").text();
    if (currentStatus == "Active"){
        $(".currentStatus").addClass('label-primary');
    } else if (currentStatus == "Declined"){
        $(".currentStatus").addClass('label-danger');
    } else {
		$(".currentStatus").addClass('label-warning');
	}
	
	$('.dataTableSearchOnly').DataTable({
		"oLanguage": {
		  "sSearch": "Search Table"
		}, pageLength: 25,
		responsive: true
	});	
});
	
   $(function(){
	    var impressions = [
		    @foreach($pub_data['last_thirty_days'] as $key => $value)
		        [{{$value['timestamp']}},{{$value['impressions']}}],
		    @endforeach
	    ];
	    var clicks = [
		    @foreach($pub_data['last_thirty_days'] as $key => $value)
		        [{{$value['timestamp']}},{{$value['clicks']}}],
	            @endforeach
	    ];
    function doPlot(position) {
        $.plot($("#flot-line-chart-multi"), [{
            data: impressions,
            label: "Impressions",
			color: "#1ab394",
			bars: {
				show: true,
				align: "center",
				barWidth: 24 * 60 * 60 * 600,
				lineWidth:0
			}
			
        }, {
            data: clicks,
            label: "Clicks",
            yaxis: 1, 
			color: "#1C84C6",
			lines: {
				lineWidth:1,
					show: true,
					fill: true,
				fillColor: {
					colors: [{
						opacity: 0.2
					}, {
						opacity: 0.4
					}]
				}
			},
			splines: {
				show: false,
				tension: 0.6,
				lineWidth: 1,
				fill: 0.1
			}
        }], {
            xaxes: [{
                mode: 'time'
            }],
            yaxes: [{
                min: 0,
				position: "left",
				color: "#d5d5d5",
				axisLabelUseCanvas: true,
				axisLabelFontSizePixels: 12,
				axisLabelFontFamily: 'Arial'				
            }, {
                // align if we are to the right
                alignTicksWithAxis: position == "right" ? 1 : null,
                position: position,
				
				//position: "right",
				clolor: "#d5d5d5",
				axisLabelUseCanvas: true,
				axisLabelFontSizePixels: 12,
				axisLabelFontFamily: ' Arial',
				axisLabelPadding: 67
				
            }],
            legend: {
                //position: 'sw',
				
				noColumns: 1,
				labelBoxBorderColor: "#000000",
				position: "nw"
            },
            colors: ["#1ab394","#1C84C6"],
            grid: {
                color: "#999999",
                hoverable: true,
                clickable: true,
                tickColor: "#D4D4D4",
                borderWidth:0,
                hoverable: true //IMPORTANT! this is needed for tooltip to work,

            },
            tooltip: true,
            tooltipOpts: {
                content: "%s for %x were %y",
                xDateFormat: "%m-%d-%Y",

                onHover: function(flotItem, $tooltipEl) {
                    console.log(flotItem, $tooltipEl);
                }
            }

        });
    }

		doPlot("right");

		$("button").click(function() {
			doPlot($(this).text());
		});
    
    }); 
	
	
</script>
   <script type="text/javascript">
       jQuery(document).ready(function ($) {
	       $('.nav-click').removeClass("active");
	       $('#nav_pub_dashboard').addClass("active");
	       $('#nav_pub').addClass("active");
	       $('#nav_pub_menu').removeClass("collapse");
       });
   </script>
@endif

<!-- start advertisement -->

@if($view_type == 2)
<div class="content">
    <div class="row">
        <div class="col-lg-12">
			<div class="row">
				<div class="col-md-12">
					<h4>Today</h4>
				</div>

				<div class="col-sm-2 widget-boxs">
					<div class="ibox float-e-margins">
						<div class="ibox-title purple-bg">
							<span class="fa fa-eye"></span>
							<span>Impressions</span>
						</div>
						<div class="ibox-content">
							<span class="totalStat">
							  {{ number_format($buyer_data['impressions_today']) }}
							</span>
						</div>
					</div>
				</div>
				<div class="col-sm-2 widget-boxs">
					<div class="ibox float-e-margins">
						<div class="ibox-title yellow-bg">
							<span class="fa fa-location-arrow"></span>
							<span>Clicks</span>
						</div>
						<div class="ibox-content">
							<span class="totalStat">
								{{ number_format($buyer_data['clicks_today']) }}
							</span>
						</div>
					</div>
				</div>
				<div class="col-sm-2 widget-boxs">
					<div class="ibox float-e-margins">
						<div class="ibox-title navy-bg">
							<span class="fa fa-money"></span>
							<span>Balance</span>
						</div>
						<div class="ibox-content">
							<span class="totalStat">{{ money_format('%(#10n',$buyer_data['current_balance']) }}</span>
						</div>
					</div>
				</div>
				<div class="col-sm-2 widget-boxs">
					<div class="ibox float-e-margins">
						<div class="ibox-title blue-bg">
							<span class="fa fa-bar-chart-o"></span>
							<span>CPM</span>
						</div>
						<div class="ibox-content">
							<span class="totalStat">{{money_format('%(#10n',$buyer_data['cpm_today']) }}</span>	
						</div>
					</div>
				</div>
				<div class="col-sm-2 widget-boxs">
					<div class="ibox float-e-margins">
						<div class="ibox-title red-bg">
							<span class="fa fa-hand-o-up"></span>
							<span>CPC</span>
						</div>
						<div class="ibox-content">
							<span class="totalStat">{{ money_format('%(#10n',$buyer_data['cpc_today']) }}</span>
						</div>
					</div>
				</div>
			</div>
			
			<div class="row">
				<div class="col-lg-4">
					<div class="ibox float-e-margins">
						<div class="ibox-title">
							<span class="label label-success pull-right">{{ date('F jS',strtotime('yesterday')) }}</span>
							<h5>Yesterday</h5>
						</div>
						<div class="ibox-content">
							<div class="stat-percent font-bold text-success pull-right">{{ number_format($buyer_data['impressions_yesterday']) }}</div>
							<small>Impressions</small><br />
							<div class="stat-percent font-bold text-success pull-right">{{ number_format($buyer_data['clicks_yesterday']) }}</div>
							<small>Clicks</small><br />
							<div class="stat-percent font-bold text-success pull-right">
								{{ number_format((float) $buyer_data['ctr_yesterday'], 3, '.', '') }} %</div>
							<small>CTR</small><br />                
							<div class="stat-percent font-bold text-success pull-right">{{money_format('%(#10n',round($buyer_data['spent_yesterday'],2)) }}</div>
							<small>Costs</small><br />
							<div class="stat-percent font-bold text-success pull-right">{{money_format('%(#10n',$buyer_data['cpm_yesterday']) }}</div>
							<small>CPM</small><br />
							<div class="stat-percent font-bold text-success pull-right">
								{{money_format('%(#10n',$buyer_data['cpc_yesterday']) }}
							</div>
							<small>CPC</small><br />
						</div>
					</div>
				</div>
				<div class="col-lg-4">
					<div class="ibox float-e-margins">
						<div class="ibox-title">
							<span class="label label-success pull-right">{{ date('F') }}</span>
							<h5>This Month</h5>
						</div>
						<div class="ibox-content">
							<div class="stat-percent font-bold text-success pull-right">{{ number_format($buyer_data['impressions_this_month']) }}</div>
							<small>Impressions</small><br />
							<div class="stat-percent font-bold text-success pull-right">{{ number_format($buyer_data['clicks_this_month']) }}</div>
							<small>Clicks</small><br />
							<div class="stat-percent font-bold text-success pull-right">
								{{ number_format((float) $buyer_data['ctr_this_month'], 3, '.', '') }} %</div>
							<small>CTR</small><br />                
							<div class="stat-percent font-bold text-success pull-right">{{ money_format('%(#10n',round($buyer_data['spent_this_month'],2)) }}</div>
							<small>Costs</small><br />
							<div class="stat-percent font-bold text-success pull-right">{{ money_format('%(#10n',$buyer_data['cpm_this_month']) }}</div>
							<small>CPM</small><br />
							<div class="stat-percent font-bold text-success pull-right">{{ money_format('%(#10n',$buyer_data['cpc_this_month']) }}</div>
							<small>CPC</small><br />
						</div>
					</div>
				</div>
				<div class="col-lg-4">
					<div class="ibox float-e-margins">
						<div class="ibox-title">
							<span class="label label-success pull-right">{{ date('F', strtotime('last month')) }}</span>
							<h5>Last Month</h5>
						</div>
						<div class="ibox-content">
							<div class="stat-percent font-bold text-success pull-right">{{ number_format($buyer_data['impressions_last_month']) }}</div>
							<small>Impressions</small><br />
							<div class="stat-percent font-bold text-success pull-right">{{ number_format($buyer_data['clicks_last_month']) }}</div>
							<small>Clicks</small><br />
							<div class="stat-percent font-bold text-success pull-right">
								{{ number_format((float) $buyer_data['ctr_last_month'], 3, '.', '') }} %</div>
							<small>CTR</small><br />                
							<div class="stat-percent font-bold text-success pull-right">{{money_format('%(#10n', round($buyer_data['spent_last_month'],2)) }}</div>
							<small>Costs</small><br />
							<div class="stat-percent font-bold text-success pull-right">{{ money_format('%(#10n',$buyer_data['cpm_last_month']) }}</div>
							<small>CPM</small><br />
							<div class="stat-percent font-bold text-success pull-right">{{money_format('%(#10n', $buyer_data['cpc_last_month']) }}</div>
							<small>CPC</small><br />
						</div>
					</div>
				</div>
			</div>
			
			<div class="row">
				<div class="col-lg-12">
					<div class="ibox float-e-margins">
						<div class="ibox-title">
							<h5>Daily Stats 2</h5>
						</div>
						<div class="ibox-content">
							<div class="row">
								<div class="col-lg-9">
									<div class="flot-chart">
										<div class="flot-chart-content" id="flot-line-chart-multi"></div>
									</div>
								</div>
								<div class="col-lg-3" id="ad-stat-list">
									<ul class="stat-list">
										<li id="ad-impressions">
											<h2 class="no-margins"> {{ number_format($buyer_data['impressions_this_month']) }} </h2>
											<small>Impressions</small>
											<div class="progress progress-mini">
												<div style="width: 100%;" class="progress-bar"></div>
											</div>
										</li>
										<li id="ad-earnings">
											<h2 class="no-margins ">{{ money_format('%(#10n',$buyer_data['cpc_this_month']) }}</h2>
											<small>Cost Per Click</small>
											<div class="progress progress-mini">
												<div style="width: 100%;" class="progress-bar"></div>
											</div>
										</li>
										<li id="ad-cpm">
											<h2 class="no-margins ">
												{{ number_format((float) $buyer_data['ctr_this_month'], 3, '.', '') }} %
											</h2>
											<small>Click Through Rate</small>
											<div class="progress progress-mini">
												<div style="width: 100%;" class="progress-bar"></div>
											</div>
										</li>
									</ul>
								</div>
							</div>

						</div>
					</div>
				</div>
			</div>

			<div class="row">
				<div class="col-xs-12 col-md-6">
					<div class="panel panel-default">
						<h4 class="p-title">Filter</h4>
						<div class="ibox-content">
							<div class="row">
								<div class="col-xs-12">
									<form name="dashboard_form" method="POST">
										<label>Dates</label>
										{{ csrf_field() }}
										<div class="row">
											<div class="col-xs-12 col-md-6 form-group">
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
					</div>
				</div>
			</div>

			<div class="row">
				<div class="col-lg-12">
					<div class="ibox float-e-margins">
						<div class="panel panel-default">
							<div class="pull-right m-t m-r">
							<a href="{{ URL::to('addfunds') }}"><span class="label label-info"><i class="fa fa-cc-visa"></i>&nbsp;<i class="fa fa-cc-mastercard"></i>&nbsp; Add Funds</span></a>&nbsp;
							<a href="javascript:void" onclick="return showThisMonth();" class="lastmonth"><span class="label label-warning lastmonth" style="display:none">Show This Month</span></a>
							<a href="javascript:void" onclick="return showLastMonth();" class="thismonth"><span class="label label-warning thismonth">Show Last Month</span></a>
							</div>	
							<h4 id="table_this_month" class="thismonth p-title">Campaigns - {{ date('F') }}</h4>
							<h4 id="table_this_month" class="lastmonth p-title" style="display:none">Campaigns - {{ date('F',strtotime('last month')) }}</h4>
							<div class="ibox-content tableSearchOnly">
								<table class="tablesaw tablesaw-stack table-striped table-hover dataTableSearchOnly dateTableFilter" data-tablesaw-mode="stack">
									<thead>
										<tr>
											<th>Date</th>
											<th>Name</th>
											<th>Days Active</th>
											<th>Impressions</th>
											<th>Clicks</th>
											<th>Cost</th>
											<!--<th>Number of Creatives</th>-->
											<th>Status</th>
										</tr>
									</thead>
									<tbody>
									@foreach($buyer_data['campaigns']['thismonth'] as $key => $campaign)
										<tr class='thismonth'>
											<td class="text-center"><b class=" tablesaw-cell-label">Date</b>{{ date('m/d/Y',strtotime($campaign['created_at'])) }} </td>
											<td class="text-center"><b class=" tablesaw-cell-label">Name</b>{{ $key }}</td>
											<td class="text-center"><b class=" tablesaw-cell-label">Days Active</b>{{ $campaign['days_active'] }} </td>
											<td class="text-center"><b class=" tablesaw-cell-label">Impressions</b>{{ $campaign['impressions'] }}</td>
											<td class="text-center"><b class=" tablesaw-cell-label">Clicks</b>{{$campaign['clicks']}}</td>
											<td class="text-center"><b class=" tablesaw-cell-label">Costs</b>{{money_format('%(#10n',$campaign['spend'])}}</td>
											<!--<td class="text-center"><b class=" tablesaw-cell-label">Number of Creatives</b>{{$campaign['status']}}</td>-->
											<td class="text-center"><b class=" tablesaw-cell-label">Status</b>
												<span class="currentStatus label"> {{ $campaign['status'] }} </span>
											</td>
										</tr>
									@endforeach
									@foreach($buyer_data['campaigns']['lastmonth'] as $key => $campaign)
										<tr class='lastmonth' style="display:none">
											<td class="text-center"><b class=" tablesaw-cell-label">Date</b>{{ date('m/d/Y',strtotime($campaign['created_at'])) }} </td>
											<td class="text-center"><b class=" tablesaw-cell-label">Name</b>{{ $key }}</td>
											<td class="text-center"><b class=" tablesaw-cell-label">Days Active</b>{{ $campaign['days_active'] }} </td>
											<td class="text-center"><b class=" tablesaw-cell-label">Impressions</b>{{$campaign['impressions']}}</td>
											<td class="text-center"><b class=" tablesaw-cell-label">Clicks</b>{{$campaign['clicks']}}</td>
											<td class="text-center"><b class=" tablesaw-cell-label">Costs</b>{{ money_format('%(#10n',$campaign['spend']) }}</td>
											<!--<td class="text-center"><b class=" tablesaw-cell-label">Number of Creatives</b>{{$campaign['status']}}</td>-->
											<td class="text-center"><b class=" tablesaw-cell-label">Status</b>
												<span class="currentStatus label">{{ $campaign['status'] }}</span>
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
	</div>
</div>
@endsection
@section('js')
<script type="text/javascript">
$( document ).ready(function() {
	$('.dataTableSearchOnly').DataTable({
		"oLanguage": {
		  "sSearch": "Search Table"
		}, "columnDefs": [{ targets: 4, type: 'numeric-comma' }],
		pageLength: 25,
		responsive: true
	});
	
	setStatus();
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
	
    function showThisMonth(){
        $(".lastmonth").hide();
        $(".thismonth").show();
        return false;
    }
    function showLastMonth(){
        $(".thismonth").hide();
        $(".lastmonth").show();
        return false;
    }
    $(function(){
	    var impressions = [
		    @foreach($buyer_data['last_thirty_days'] as $key => $value)
		        [{{$value['timestamp']}},{{$value['impressions']}}],
		    @endforeach
	    ];
	    var clicks = [
		    @foreach($buyer_data['last_thirty_days'] as $key => $value)
		        [{{$value['timestamp']}},{{$value['clicks']}}],
	            @endforeach
	    ];
		
		    function doPlot(position) {
        $.plot($("#flot-line-chart-multi"), [{
            data: impressions,
            label: "Impressions",
			color: "#1ab394",
			bars: {
				show: true,
				align: "center",
				barWidth: 24 * 60 * 60 * 600,
				lineWidth:0
			}
			
        }, {
            data: clicks,
            label: "Clicks",
            yaxis: 1, 
			color: "#1C84C6",
			lines: {
				lineWidth:1,
					show: true,
					fill: true,
				fillColor: {
					colors: [{
						opacity: 0.2
					}, {
						opacity: 0.4
					}]
				}
			},
			splines: {
				show: false,
				tension: 0.6,
				lineWidth: 1,
				fill: 0.1
			}
        }], {
            xaxes: [{
                mode: 'time'
            }],
            yaxes: [{
                min: 0,
				position: "left",
				color: "#d5d5d5",
				axisLabelUseCanvas: true,
				axisLabelFontSizePixels: 12,
				axisLabelFontFamily: 'Arial'				
            }, {
                // align if we are to the right
                alignTicksWithAxis: position == "right" ? 1 : null,
                position: position,
				
				//position: "right",
				clolor: "#d5d5d5",
				axisLabelUseCanvas: true,
				axisLabelFontSizePixels: 12,
				axisLabelFontFamily: ' Arial',
				axisLabelPadding: 67
				
            }],
            legend: {
                //position: 'sw',
				
				noColumns: 1,
				labelBoxBorderColor: "#000000",
				position: "nw"
            },
            colors: ["#1ab394","#1C84C6"],
            grid: {
                color: "#999999",
                hoverable: true,
                clickable: true,
                tickColor: "#D4D4D4",
                borderWidth:0,
                hoverable: true //IMPORTANT! this is needed for tooltip to work,

            },
            tooltip: true,
            tooltipOpts: {
                content: "%s for %x were %y",
                xDateFormat: "%m-%d-%Y",

                onHover: function(flotItem, $tooltipEl) {
                    console.log(flotItem, $tooltipEl);
                }
            }

        });
    }

    	doPlot("right");

		$("button").click(function() {
			doPlot($(this).text());
		});
    });
</script>
   <script type="text/javascript">	
       jQuery(document).ready(function ($) {
	       $('.nav-click').removeClass("active");
	       $('#nav_buyer_dashboard').addClass("active");
	       $('#nav_buyer').addClass("active");
	       $('#nav_buyer_menu').removeClass("collapse");  
		   
	       @if(session('status'))
		       toastr.success('{{session('status')}}');
	       @endif
		   
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
@endif
@endsection
