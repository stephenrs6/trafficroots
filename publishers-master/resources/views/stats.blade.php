@extends('layouts.app')
@section('title', 'Sites')
@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-8 col-md-offset-2">
            <div class="ibox">
                <div class="ibox-title">{{ $site->site_name }} - {{ $site->site_url }}</div>

                <div class="ibox-content">
                    <p>Aggregate Statistics - {{ $range }}</p>
                    <div class="row"><div class="col-md-3">Impressions</div><div class="col-md-9">{{ $imps }}</div></div>
                    <div class="row"><div class="col-md-3">Clicks</div><div class="col-md-9">{{ $clicks }}</div></div>
                    <div class="row"><div class="col-md-3">Zone Count</div><div class="col-md-9">{{ $zone_count }}</div></div>
                    <div class="row"><div class="col-md-3">Site Data</div><div class="col-md-9"><pre>{!! var_dump($sitedata); !!}</pre></div></div>
                    <div class="row"><div class="col-md-3">BIG Data</div><div class="col-md-9"><pre>{!! var_dump($big); !!}</pre></div></div>
                    <!-- make some graphs here -->
                    <canvas id="dateChart" width="800" height="400"></canvas>

                </div>
            </div>
        </div>
    </div>
</div>
   <script type="text/javascript">
       jQuery(document).ready(function ($) {
               $('.nav-click').removeClass("active");
	       $('#nav_pub_sites').addClass("active");
       });
   </script>
@endsection
