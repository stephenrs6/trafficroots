@extends('layouts.app')
@section('title', '- Advertiser FAQ')
@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-10 col-md-offset-1">
            <div class="ibox">
                <div class="ibox-title">Publisher FAQ</div>

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
	       $('#nav_pub_faq').addClass("active");
	       $('#nav_pub').addClass("active");
	       $('#nav_pub_menu').removeClass("collapse");
       });
   </script>
@endsection

