@extends('layouts.app')

@section('title','Active Campaigns')
@section('css')
@endsection

@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-12">
            <div class="ibox">
                    <div class="ibox-title">Zone Preview - Active Campaigns</div>
                    <div class="ibox-content table-responsive">
                        @if (count($campaigns))
                            <table class="table table-hover table-border table-striped table-condensed" name="campaigns_table" id="campaigns_table" width="100%">
                            <thead>
                            <tr><th>Bid Id</th><th>Category</th><th>Impressions Today</th><th>Clicks Today</th><th>Preview</th></tr>
                            </thead>
                            <tbody>
                            @foreach ($campaigns as $campaign)
                                <tr>
                                    <td>{{ $campaign->bid_id }} </td>
                                    <td> {{ $campaign->category }} </td>
                                    <td> {{ $campaign->impressions }} </td>
                                    <td> {{ $campaign->clicks }} </td>
                                    <td><a href="/preview/{{ $campaign->bid_id }}"><button class="btn bt-xs alert-info"><span class="btn-label"><i class="fa fa-cogs"></span></i> View Banners</button></a></td>
                                </tr>
                   
                            @endforeach
                   
                            </tbody>
                            </table>
                        @else
                            <h3>No Campaigns Defined</h3> 
 
                        @endif
                    </div>
            </div>
        </div>
    </div>
</div>
   <script type="text/javascript">
       jQuery(document).ready(function ($) {
	       $('.nav-click').removeClass("active");
	       $('#nav_pub_menu').addClass("active");
	       $('#nav_pub_dashboard').addClass("active");
	       $('#nav_pub_menu').removeClass("collapse");
       });
   </script>
@endsection
