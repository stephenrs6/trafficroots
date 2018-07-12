@extends('layouts.app')
@section('css')
<link rel="stylesheet"
      href="{{ URL::asset('css/plugins/daterangepicker/daterangepicker.css') }}">
<link rel="stylesheet"
      href="{{ URL::asset('css/plugins/select2/select2.min.css') }}">
<link rel="stylesheet"
      href="{{ URL::asset('css/custom.css') }}">
    <link rel="stylesheet" href="//cdnjs.cloudflare.com/ajax/libs/highlight.js/9.12.0/styles/github.min.css">
    <style type="text/css">
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
.amchartdiv {
    width	: 100%;
    height	: 500px;
}	


    </style>

@endsection
@section('js')
    <script src="{{ URL::asset('js/plugins/footable/footable.all.min.js') }}"></script>
    <script src="{{ URL::asset('js/plugins/daterangepicker/daterangepicker.js') }}"></script>
    <script src="{{ URL::asset('js/plugins/select2/select2.full.min.js') }}"></script>


@endsection
@section('content')

<!-- Resources -->
<script src="https://www.amcharts.com/lib/3/amcharts.js"></script>
<script src="https://www.amcharts.com/lib/3/serial.js"></script>
<script src="https://www.amcharts.com/lib/3/pie.js"></script>
<script src="https://www.amcharts.com/lib/3/plugins/export/export.min.js"></script>
<link rel="stylesheet" href="https://www.amcharts.com/lib/3/plugins/export/export.css" type="text/css" media="all" />
<script src="https://www.amcharts.com/lib/3/themes/light.js"></script>

                           <div class="ibox">
                            <div class="ibox-title">Zone Stats - {{ $zone }}  - <span id="dateRangeDisplay">{{ $startDate }}@if($endDate) - {{ $endDate }}@endif</span> </div>
			    <div class="ibox-content">
                        <div class="row">
                                <div class="col-md-12">
                                        <div class="panel panel-default">
                                                <h4 class="p-title">Filter</h4>
                                                <div class="ibox-content">
                                                        <div class="row">
                                                                <div class="col-xs-12 col-md-5">
                                                                <form name="filter_form"
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
                            <div class="ibox"><div class="ibox-content">
                            <h4>Traffic - {{ $todays_traffic }} impressions - {{ $todays_clicks }} clicks - CTR {{ $todays_ctr }} %</h4>
                            <!-- <div id="sitechart" class="amchartdiv"></div> -->
                            </div></div>
                            <div class="ibox"><div class="ibox-content">
                            <h4>Geo Breakdown (Top 20)</h4>
                            <div id="geochartdiv" class="amchartdiv"></div>
                            </div></div>
                            <div class="ibox"><div class="ibox-content">
                            <h4>US State Breakdown (Top 20)</h4>
                            <div id="statechartdiv" class="amchartdiv"></div>
			    </div></div>
                            <div class="ibox"><div class="ibox-content">
                            <h4>Device Types</h4>
                            <div id="devicechart" class="amchartdiv"></div>
			    </div></div>
                            <div class="ibox"><div class="ibox-content">
                            <h4>Operating Systems</h4>
                            <div id="oschart" class="amchartdiv"></div>
			    </div></div>
                            <div class="panel panel-default"><div class="panel-body">
                            <h4>Browsers</h4>
                            <div id="browserchart" class="amchartdiv"></div>
			    </div></div>

                            </div>
                            </div>

<script type="text/javascript">
$(document).ready(function(){
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

     var chart = AmCharts.makeChart("browserchart", {
    "type": "pie",
    "theme": "light",
    "innerRadius": "40%",
    "gradientRatio": [-0.4, -0.4, -0.4, -0.4, -0.4, -0.4, 0, 0.1, 0.2, 0.1, 0, -0.2, -0.5],
    "dataProvider": browserData,
    "balloonText": "[[value]]",
    "valueField": "impressions",
    "titleField": "site",
    "balloon": {
        "drop": true,
        "adjustBorderColor": false,
        "color": "#FFFFFF",
        "fontSize": 16
    },
    "export": {
        "enabled": true
    }
    });
   
	var chart = AmCharts.makeChart("devicechart", {
    "type": "pie",
    "theme": "light",
    "innerRadius": "40%",
    "gradientRatio": [-0.4, -0.4, -0.4, -0.4, -0.4, -0.4, 0, 0.1, 0.2, 0.1, 0, -0.2, -0.5],
    "dataProvider": deviceData,
    "balloonText": "[[value]]",
    "valueField": "impressions",
    "titleField": "site",
    "balloon": {
        "drop": true,
        "adjustBorderColor": false,
        "color": "#FFFFFF",
        "fontSize": 16
    },
    "export": {
        "enabled": true
    }
    });
      var chart = AmCharts.makeChart("oschart", {
    "type": "pie",
    "theme": "light",
    "innerRadius": "40%",
    "gradientRatio": [-0.4, -0.4, -0.4, -0.4, -0.4, -0.4, 0, 0.1, 0.2, 0.1, 0, -0.2, -0.5],
    "dataProvider": osData,
    "balloonText": "[[value]]",
    "valueField": "impressions",
    "titleField": "site",
    "balloon": {
        "drop": true,
        "adjustBorderColor": false,
        "color": "#FFFFFF",
        "fontSize": 16
    },
    "export": {
        "enabled": true
    }
    });
/*
     
    var chart = AmCharts.makeChart("sitechart", {
    "type": "pie",
    "theme": "light",
    "innerRadius": "40%",
    "gradientRatio": [-0.4, -0.4, -0.4, -0.4, -0.4, -0.4, 0, 0.1, 0.2, 0.1, 0, -0.2, -0.5],
    "dataProvider": siteData,
    "balloonText": "[[value]]",
    "valueField": "impressions",
    "titleField": "site",
    "balloon": {
        "drop": true,
        "adjustBorderColor": false,
        "color": "#FFFFFF",
        "fontSize": 16
    },
    "export": {
        "enabled": true
    }
    });
 */
    /* default geo traffic chart */
var chart = AmCharts.makeChart("geochartdiv", {
    "theme": "light",
    "type": "serial",
    "startDuration": 2,
    "dataProvider": defaultGeoData,
    "valueAxes": [{
        "position": "left",
        "axisAlpha":0,
        "gridAlpha":0
    }],
    "graphs": [{
        "balloonText": "[[cname]]: <b>[[value]]</b>",
        "colorField": "color",
        "fillAlphas": 0.85,
        "lineAlpha": 0.1,
        "type": "column",
        "topRadius":1,
        "valueField": "impressions"
    }],
    "depth3D": 40,
	"angle": 30,
    "chartCursor": {
        "categoryBalloonEnabled": false,
        "cursorAlpha": 0,
        "zoomable": false
    },
    "categoryField": "country",
    "categoryAxis": {
        "gridPosition": "start",
        "axisAlpha":0,
        "gridAlpha":0

    },
    "export": {
    	"enabled": true
     }

}, 0);

    /* default state traffic chart */
var chart = AmCharts.makeChart("statechartdiv", {
    "theme": "light",
    "type": "serial",
    "startDuration": 2,
    "dataProvider": defaultStateData,
    "valueAxes": [{
        "position": "left",
        "axisAlpha":0,
        "gridAlpha":0
    }],
    "graphs": [{
        "balloonText": "[[sname]]: <b>[[value]]</b>",
        "colorField": "color",
        "fillAlphas": 0.85,
        "lineAlpha": 0.1,
        "type": "column",
        "topRadius":1,
        "valueField": "impressions"
    }],
    "depth3D": 40,
	"angle": 30,
    "chartCursor": {
        "categoryBalloonEnabled": false,
        "cursorAlpha": 0,
        "zoomable": false
    },
    "categoryField": "state",
    "categoryAxis": {
        "gridPosition": "start",
        "axisAlpha":0,
        "gridAlpha":0

    },
    "export": {
    	"enabled": true
     }

}, 0);

});
 var chartColors = [];
chartColors.push("#FF0F00");
chartColors.push("#FF6600");
chartColors.push("#FCD202");
chartColors.push("#F8FF01");
chartColors.push("#B0DE09");
chartColors.push("#04D215");
chartColors.push("#0D8ECF");
chartColors.push("#0D52D1");
chartColors.push("#2A0CD0");
chartColors.push("#8A0CCF");
chartColors.push("#CD0D74");
chartColors.push("#754DEB");
chartColors.push("#DDDDDD");
chartColors.push("#333333");
chartColors.push("#555555");
chartColors.push("#3B7F04");
chartColors.push("#EEEFFF");
chartColors.push("#0EF0EE");
chartColors.push("#FE3ECF");
chartColors.push("#CCCEEE");

var siteData = [];
@if(isset($site_traffic))
@foreach($site_traffic as $row)
siteData.push({
    site: '{{ $row['site_name'] }}',
    impressions: {{ $row['impressions'] }}
});
@endforeach
	@endif

 var defaultGeoData = [];
 var index = 0;
@foreach($geo_traffic as $row)
defaultGeoData.push({
    country: '{{ $row->country_short }}',
	    impressions: {{ $row->impressions }},
	    cname: '{{ $row->country_name }}',
	    color: chartColors[index]
 });
 index = (index + 1);
@endforeach
index = 0;
var defaultStateData = [];
@foreach($state_traffic as $row)
defaultStateData.push({
    state: '{{ $row->state_short }}',
    impressions: {{ $row->impressions }},
    sname: '{{ $row->state_name }}',
    color: chartColors[index]
});
index = (index + 1);
@endforeach
var deviceData = [];
@if(isset($platforms))
@foreach($platforms as $row)
deviceData.push({
    site: '{{ $row->description }}',
    impressions: {{ $row->impressions }}
});
@endforeach
@endif
var osData = [];
@if(isset($operating_systems))
@foreach($operating_systems as $row)
osData.push({
    site: '{{ $row->description }}',
    impressions: {{ $row->impressions }}
});
@endforeach
@endif
var browserData = [];
@if(isset($browsers))
@foreach($browsers as $row)
browserData.push({
    site: '{{ $row->description }}',
    impressions: {{ $row->impressions }}
});
@endforeach
@endif

</script>
@endsection
