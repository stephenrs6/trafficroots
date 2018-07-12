<?php
use App\Site;
use App\StatusType;
use App\Zone;
use App\AdCreative;
?>
@extends('layouts.app')

@section('title','Zone Management')
@section('css')
<link rel="stylesheet"
      href="//cdnjs.cloudflare.com/ajax/libs/highlight.js/9.12.0/styles/github.min.css">
<link href="{{ URL::asset('css/plugins/footable/footable.core.css') }}"
      rel="stylesheet">
<link href="{{ URL::asset('css/plugins/iCheck/custom.css') }}"
      rel="stylesheet">
<style type="text/css">
.footable th:last-child .footable-sort-indicator {
    display: none;
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
@endsection
@section('content')
<div class="content">
<div class="row">
    <div class="col-lg-12">
		<div class="flash-message">
		  @foreach (['danger', 'warning', 'success', 'info'] as $msg)
			@if(Session::has('alert-' . $msg))
			<div class="alert alert-{{ $msg }}">{{ Session::get('alert-' . $msg) }}</div>
			@endif
		  @endforeach
		</div>	
        <div class="ibox">
			<div class="panel panel-default">
				<div class="pull-right m-t m-r">
					<a href="/sites" class="btn btn-primary btn-xs">
                    <span class="fa fa-arrow-circle-left"></span>&nbsp;Back to Sites</a>
					<a href="/custom_ad/{{ $zone->handle }}">
						<button class="btn btn-xs btn-info"><i class="fa fa-file-code-o"></i> Create Custom Ad</button>
					</a>
				</div>
            	<h4 class="p-title">Manage Zone {{$zone->handle}}</h4>
				<div class="ibox-content">
					<div class="tableSearchOnly">
						<table class="tablesaw tablesaw-stack table-striped table-hover dataTableSearchOnly dateTableFilter" data-tablesaw-mode="stack">           
							<thead><tr><th>Ad</th><th>Weight</th><th>Status</th><th>Options</th></tr></thead>
							<tbody>
							@foreach($ads as $ad)
							@if($ad->buyer_id)
							<tr><td>{{$ad->description}}</td><td>{{$ad->weight}}</td><td>{{ StatusType::where('id', $ad->status)->first()->description }}</td><td>
							@if($ad->status == 1)
									<a href="/pause_custom_ad/{{ $ad->id }}">
														<button class="btn btn-xs alert-warning"><i class="fa fa-pause"></i> Pause</button>
									</a>
							@endif
							@if($ad->status == 3)
									<a href="/resume_custom_ad/{{ $ad->id }}">
														<button class="btn btn-xs alert-success"><i class="fa fa-play"></i> Play</button>
									</a>
							@endif         
							&nbsp;
									<a href="/edit_custom_ad/{{ $ad->id }}">
														<button class="btn btn-xs alert-info"><i class="fa fa-edit"></i> Edit</button>
									</a>
							</td></tr>
							@else
							<tr><td>{{$ad->description}}</td><td>{{$ad->weight}}</td><td>TrafficRoots RTB</td><td>&nbsp;</td></tr>
							@endif
							@endforeach
							</tbody>
						</table>
					</div>
				</div>
			</div>
        </div>
         <div class="ibox">
            <div class="ibox-title"><h2>Knowledge Base</h2></div>
            <div class="ibox-content">
            <ul>
                <li>The "Default" Ad is the TrafficRoots RTB System.</li>
                <li>Each newly created Zone starts with 75% weight on the Default Ad.</li>
		<li>You can create your own <a href ="javascript:void;" title="A `Custom Ad` is a Publisher controlled, targeted campaign, configured to take some or all of the traffic weight on a given zone">Custom Ads</a>, directing traffic where you wish.</li>
                <li>Each Custom Ad can have One or Multiple <a href="javascript:void;" title="A `Creative` is a combination of a Media banner/image and a destination Link">Creatives</a></li>
		<li>You are responsible for the content of your Custom Ads.</li>
                <li>Weight of the Default Ad can be reduced to 0, but it cannot be removed.</li>
                <li>Once a Custom Ad has traffic, it can only be soft-deleted.</li>
            </ul>
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
		   
		   
	   $('.nav-click').removeClass("active");
	   $('#nav_pub_sites').addClass("active");
	   $('#nav_pub').addClass("active");
	   $('#nav_pub_menu').removeClass("collapse");

       });
   </script>
@endsection
