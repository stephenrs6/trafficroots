@extends('layouts.app')
@section('title', 'Frequently Asked Questions')

@section('css')
    <link href="{{ URL::asset('css/style.css') }}" rel="stylesheet">
@endsection

@section('content')
	<div class="content">
		<div class="row">
			<div class="col-xs-12">
				<ul id="tabs" class="nav nav-tabs" data-tabs="tabs">
                    <li  class="active"><a data-toggle="tab">FAQ</a></li>
<!--                    <li><a href="" data-toggle="tab">State Regulations</a></li>-->
                </ul>
				<div class="panel panel-default" style="border: none;">
					<div class="ibox-content">
						@foreach($faqs as $faq)
						<div class="faq-item">
							<div class="row">
								<div class="col-lg-12">
									<a data-toggle="collapse" href="#faq{{ $faq->id }}" class="faq-question">
										{{ $faq->question }}
									</a>                                   
								</div>
							</div>
							<div class="row">
								<div class="col-lg-12">
									<div id="faq{{ $faq->id }}" class="panel-collapse collapse">
										<div class="faq-answer">
											<p>
											 {!! $faq->answer !!}
											</p>
										</div>
									</div>
								</div>
							</div>
						</div>
						@endforeach
					</div>
				</div>
			</div>
		</div>
	</div>
   <script type="text/javascript">
       jQuery(document).ready(function ($) {
	       $('.nav-click').removeClass("active");
	       $('#nav_buyer_faq').addClass("active");
       });
   </script>
@endsection

