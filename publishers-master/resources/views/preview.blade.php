@extends('layouts.app')

@section('title','Campaign Preview')
@section('content')
<div class="wrapper wrapper-content">
<h3>Campaign Images and Links</h3>
@foreach($media as $row)
<div class="contact-box">
{!! $row !!}
</div>
@endforeach
<h3>Links</h3>
@foreach($links as $link)
<div class="contact-box">
   <i class="fa fa-link"></i>  {!! $link !!}
</div>
@endforeach
</div>
@endsection
