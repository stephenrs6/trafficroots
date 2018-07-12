@extends('layouts.app')
@section('title', '- New Zone!')
@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-8 col-md-offset-2">
            <div class="ibox">
            <div class="ibox-title">Add a New Zone on {{ $site->site_name }}</div>
            <div class="ibox-content">
            <form name="zone_form" id="zone_form" action="" method="POST">
       <div class="control-group">
            <label class="control-label" for="description">Zone Description</label>
            <div class="controls">
                <input type="text" size="45" maxlength="60" id="description" name="description" class="border-radius-none" placeholder="describe your zone..." required>
            </div>
        </div>
        <div class="control-group">
            <label class="control-label" for="location_type">Location Type</label>
            <div class="controls">
                <select id="location_type" name="location_type" class="border-radius-none"  required>
                <option value="">Choose One</option>
                {!! $location_types !!}
                </select>
            </div>
        </div>

        <div class="control-group">
            {{ csrf_field() }}
            <input type="hidden" name="site_id" id="site_id" value="{{ $site->id }}">
            <br /><br /><div class="controls">
                <input type="submit" value="Continue">
            </div>
        </div>
            </form>
        </div>
    </div>
        </div>
    </div>
</div>
   <script type="text/javascript">
       jQuery(document).ready(function ($) {
               $('.nav-click').removeClass("active");
               $('#nav_pub').addClass("active");
       });
   </script>
@endsection
