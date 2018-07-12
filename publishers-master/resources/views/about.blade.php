@extends('layouts.app1')
@section('title', '- About Us')
@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-8 col-md-offset-2">
            <div class="ibox">
                <div class="ibox-title">About Us</div>

                <div class="ibox-content">
                    <p>TrafficRoots was founded by digital marketing veterans intent on creating the largest cannabis focused ad network in the US and around the Globe!</p>
                    @if (Auth::guest())
                    <p><a href="/register ">Join Us Today!</a></p>
                    @else
                    <p>Thank you for being our valued Publisher! We appreciate your trust and business!</p>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
