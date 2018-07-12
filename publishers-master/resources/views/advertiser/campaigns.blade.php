@extends('layouts.app')
@section('title','Campaigns')
@section('css')
<link rel="stylesheet"
      href="{{ URL::asset('css/plugins/daterangepicker/daterangepicker.css') }}">
<link rel="stylesheet"
      href="{{ URL::asset('css/plugins/select2/select2.min.css') }}">
<link rel="stylesheet"
      href="{{ URL::asset('css/custom.css') }}">
<link rel="stylesheet"
      href="{{ URL::asset('css/plugins/tablesaw/tablesaw.css') }}">
    <link rel="stylesheet" href="//cdnjs.cloudflare.com/ajax/libs/highlight.js/9.12.0/styles/github.min.css">
    <link href="{{ URL::asset('css/plugins/footable/footable.core.css') }}" rel="stylesheet">
    <style type="text/css">
        .footable th:last-child .footable-sort-indicator {
            display: none;
            pointer-events: none;
        }
        .hide {
            display: none;
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
   #reportrange {
    width: unset;
    }

    .chosen-select {
            width: 100%;
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
    <script src="{{ URL::asset('js/plugins/daterangepicker/daterangepicker.js') }}"></script>
    <script src="{{ URL::asset('js/plugins/select2/select2.full.min.js') }}"></script>
@endsection
@section('content')
<div class="content">
    <div class="row">
        <div class="col-lg-12">
			<div class="row">
				<div class="col-xs-12 col-md-6">
					<div class="panel panel-default">
						<h4 class="p-title">Filter</h4>
						<div class="ibox-content">
							<div class="row">
								<div class="col-xs-12">
									<form name="campaign_form"
								  method="POST">
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
						<div class="panel panel-body">
							<h4 class="p-title" style="display:inline-block;">Campaigns - <span id="dateRangeDisplay">{{ $startDate }}@if($endDate) - {{ $endDate }}@endif</span> </h5>
							<div class="pull-right m-t m-r"><a href="{{ URL::to('addfunds') }}" class="btn btn-xs btn-info"><i class="fa fa-cc-visa"></i>&nbsp;<i class="fa fa-cc-mastercard"></i>&nbsp; Add Funds</a>&nbsp;<a href="{{ URL::to('campaign') }}" class="btn btn-xs btn-primary"><i class="fa fa-plus-square-o"></i>&nbsp;&nbsp; New Campaign</a>
							</div>

						<div class="ibox-content tableSearchOnly">
							<table class="tablesaw tablesaw-stack table-striped table-hover dataTableSearchOnly dateTableFilter" data-tablesaw-mode="stack" style="display: block; overflow-x: auto; white-space: nowrap;;">
							<thead>
									<tr>
										<th>Date</th>
										<th>Name</th>
										<th>Category</th>
										<th>Impressions <span class="fa fa-question-circle" data-toggle="tooltip" data-placement="top" title=" Number of times Advertising Material is served to a person visiting the Publisher’s Website"></span></th>
										<th>Clicks</th>
										<th>Bid <span class="fa fa-question-circle" data-toggle="tooltip" data-placement="top" title="Amount that an advertiser is willing to pay for a click or a thousand impressions."></span></th>
										<th>Type</th>
										<th>eCPM <span class="fa fa-question-circle" data-toggle="tooltip" data-placement="top" title="Used to calculate the effectiveness of an advertising campaign, independently of the actual pricing model. (CPC, CPM, CPA…)."></span></th>
										<th>Cost</th>
										<th>Status</th>
										<th>Options</th>
									</tr>
							</thead>
							<tbody>
								@foreach ($campaigns as $campaign)
								<tr>
									<td class="text-center"><b class=" tablesaw-cell-label">Date</b><div>
										{{ Carbon\Carbon::parse($campaign->created_at)->format('m/d/Y') }}</div>
									</td>
									<td class="text-center"><b class=" tablesaw-cell-label">Name</b><div>{{ $campaign->campaign_name }}</div></td>
									<td class="text-center"><b class=" tablesaw-cell-label">Category</b><div>{{ $campaign->category->category }}</div></td>
									<td><b class=" tablesaw-cell-label">Impressions</b><div>{{ $campaign->stats->sum('impressions') }}</div></td>
									<td class="text-center"><b class=" tablesaw-cell-label">Clicks</b> {{ $campaign->stats->sum('clicks') }}</td>
									<td class="text-center"><b class=" tablesaw-cell-label">Bid</b>
										${{ $campaign->bid }}
									</td>
									<td class="text-center"><b class=" tablesaw-cell-label">Type</b>
						<span class="label<?php if($campaign->type->id == 1){echo ' label-info';}else{echo ' label-success';}?>">{{ $campaign->type->campaign_type }}</span>
									</td>
									<td class="text-center"><b class=" tablesaw-cell-label">eCPM</b> $ @if($campaign->stats->sum('impressions')){{
										number_format(
												$campaign->stats->reduce(function($cost, $stat) {
													return $cost + (($stat->impressions / 1000) * $stat->cpm);
												}) * 1000 / $campaign->stats->sum('impressions'), 2
											)
										}}@else()0
										@endif

									</td>
									<td class="text-center"><b class=" tablesaw-cell-label">Cost</b> ${{ number_format(
												$campaign->stats->reduce(function($cost, $stat) {
													return $cost + (($stat->impressions / 1000) * $stat->cpm);
												}), 2
											) }}</td>
									<td class="text-center"><b class=" tablesaw-cell-label">Status</b> <label class="label label-{{ $campaign->status_type->classname }}">{{ $campaign->status_type->description }}</label></td>
									<td class="text-center"><b class=" tablesaw-cell-label">Options</b>
										<a href="{{ url("stats/campaign/$campaign->id") }}">
											<button class="campaign-stats btn btn-xs btn-warning alert-info">
												<span class="btn-label">
													<i class="fa fa-line-chart"></i>
												</span>&nbsp; Stats&nbsp;&nbsp;
											</button>
										</a>
										<a href="{{ URL::to("/manage_campaign/$campaign->id") }}" >
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

      // console.log("Current Status Text determining Color====", currentStatus)
							   
		var bidType = $(".badge").text();
		if (bidType == "CPM"){
			$(".badge").addClass('label-success');
		} else {
			$(".badge").addClass('label-danger');
		}

       jQuery(document).ready(function ($) {
	       $('.nav-click').removeClass("active");
	       $('#nav_buyer_campaigns').addClass("active");
	       $('#nav_buyer').addClass("active");
	       $('#nav_buyer_menu').removeClass("collapse");
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
