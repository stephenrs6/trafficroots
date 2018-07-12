@extends('layouts.app')
@section('title', 'Register')
@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-8 col-md-offset-2">
                <span>Please tell us about yourself and your goals</span> 
                <a href="javascript:void" onclick="return showPub();">
                <div class="widget style1 navy-bg">
                        <div class="text-center">
                            <h1 class="font-bold">I'm a Publisher</h1>
                            <span>I have a website where I have advertising space.</span>
                        </div>
                </div>
                </a>
                <div class="ibox-content animated fadeInUp" style="display:none" id="pub_div">
                    <h2>Steps to Success:</h2>
                    <small>Publisher ToDo List for new users</small>
                    <ul class="todo-list m-t">
                        <li>
                            <input type="checkbox" value="" name="" id="pubstep1" class="i-checks pubsteps"/>
                            <span class="m-l-xs">Create a Site definition.</span>
                            <small class="label label-primary"><i class="fa fa-clock-o"></i> 1 mins</small>
                        </li>
                        <li>
                            <input type="checkbox" value="" name="" id="pubstep2" class="i-checks pubsteps"/>
                            <span class="m-l-xs">Select Allowed Advertisement Categories</span>
                            <small class="label label-info"><i class="fa fa-clock-o"></i> 3 mins</small>
                        </li>
                        <li>
                            <input type="checkbox" value="" name="" id="pubstep3" class="i-checks pubsteps" />
                            <span class="m-l-xs">Create / Autocreate Zones</span>
                            <small class="label label-warning"><i class="fa fa-clock-o"></i> 2 mins</small>
                        </li>
                        <li>
                            <input type="checkbox" value="" name="" id="pubstep4" class="i-checks pubsteps"/>
                            <span class="m-l-xs">Install Advertisement Invocation Code on your Site.</span>
                            <small class="label label-danger"><i class="fa fa-clock-o"></i> 10-30 mins</small>
                        </li>
                    </ul>
                    <div class="row"><div class="col-md-12 text-center">
                    <br /><a href="/pub_type"><button class="btn btn-primary dim" id="pubContinue">Continue</button></a>
                    </div></div>                    
                </div>
                <a href="javascript:void" onclick="return showBuyer();">
                <div class="widget style1 lazur-bg">
                        <div class="text-center">
                            <h1 class="font-bold">I'm an Advertiser</h1>
                            <span>I have a product or service that I would like to advertise.</span>
                        </div>
                </div>
                </a>
                <div class="ibox-content animated fadeInUp" style="display:none" id="buyer_div">
                    <h2>Steps to Success:</h2>
                    <small>Advertiser ToDo List for new users</small>
                    <ul class="todo-list m-t">
                        <li>
                            <input type="checkbox" value="" name="" id="buyerstep1" class="i-checks buyersteps"/>
                            <span class="m-l-xs">Upload Advertising Images.</span>
                            <small class="label label-primary"><i class="fa fa-clock-o"></i> 1 mins</small>
                        </li>
                        <li>
                            <input type="checkbox" value="" name="" id="buyerstep2" class="i-checks buyersteps"/>
                            <span class="m-l-xs">Create Links to your Product or Landing Page.</span>
                            <small class="label label-info"><i class="fa fa-clock-o"></i> 3 mins</small>
                        </li>
                        <li>
                            <input type="checkbox" value="" name="" id="buyerstep3" class="i-checks buyersteps" />
                            <span class="m-l-xs">Create a Campaign</span>
                            <small class="label label-warning"><i class="fa fa-clock-o"></i> 2 mins</small>
                        </li>
                        <li>
                            <input type="checkbox" value="" name="" id="buyerstep4" class="i-checks buyersteps"/>
                            <span class="m-l-xs">Select Targeting and Set your Bid.</span>
                            <small class="label label-danger"><i class="fa fa-clock-o"></i> 3 mins</small>
                        </li>
                    </ul>
                    <div class="row"><div class="col-md-12 text-center">
                    <br /><a href="/buyer_type"><button class="btn btn-primary dim" id="pubContinue">Continue</button></a>
                    </div></div>
                </div>
                <a href="javascript:void" onclick="return showBoth();">
                <div class="widget style1 yellow-bg">
                        <div class="text-center">
                            <h1 class="font-bold">I'm Both!</h1>
                            <span>I buy and sell traffic.</span>
                        </div>
                </div>
                </a>
                <div class="ibox-content animated fadeInUp" style="display:none" id="both_div">
                   <form id="both_form" role="form" class="form-horizontal" action="/pub_form" method="POST">
                       {{ csrf_field() }}

                   </form>
                </div>
        </div>
    </div>
</div>
   <script type="text/javascript">
       var pubShowing = false;
       var buyerShowing = false;
       var bothShowing = false;
       var myInterval = false;
       function showPub(){
           if(buyerShowing){
               buyerShowing = false;
               $("#buyer_div").fadeOut();
           }
           if(bothShowing){
               bothShowing = false;
               $("#both_div").fadeOut();
           }
           if(pubShowing){
               pubShowing = false;
               $("#pub_div").fadeOut();
           }else{
               pubShowing = true;
               $('.pubsteps').prop('checked', false);
               $("#pub_div").show();
               $('html, body').animate({
                   scrollTop: $("#pub_div").offset().top
               }, 500);
           }
           if(myInterval){
               clearInterval(myInterval);
           }
           myInterval = setInterval(function(){ 
               if(!$('#pubstep1').is(":checked")){
                   $('#pubstep1').prop('checked', true);
               }else if(!$('#pubstep2').is(":checked")){
                   $('#pubstep2').prop('checked', true);
               }else if(!$('#pubstep3').is(":checked")){
                   $('#pubstep3').prop('checked', true);
               }else if(!$('#pubstep4').is(":checked")){
                   $('#pubstep4').prop('checked', true);
               }else{
                   $('.pubsteps').prop('checked', false);
               } 
           }, 800);
           return false;
       }
       function showBuyer(){
           if(pubShowing){
               pubShowing = false;
               $("#pub_div").fadeOut();
           }
           if(bothShowing){
               bothShowing = false;
               $("#both_div").fadeOut();
           }
           if(buyerShowing){
               buyerShowing = false;
               $("#buyer_div").fadeOut();
           }else{
               buyerShowing = true;
               $("#buyer_div").show();
               $('html, body').animate({
                   scrollTop: $("#buyer_div").offset().top
               }, 500);
           }
           myInterval = setInterval(function(){
               if(!$('#buyerstep1').is(":checked")){
                   $('#buyerstep1').prop('checked', true);
               }else if(!$('#buyerstep2').is(":checked")){
                   $('#buyerstep2').prop('checked', true);
               }else if(!$('#buyerstep3').is(":checked")){
                   $('#buyerstep3').prop('checked', true);
               }else if(!$('#buyerstep4').is(":checked")){
                   $('#buyerstep4').prop('checked', true);
               }else{
                   $('.buyersteps').prop('checked', false);
               }
           }, 800);
           return false;
       }
       function showBoth(){
           if(bothShowing){
               bothShowing = false;
               $("#both_div").fadeOut();
           }else{
               bothShowing = true;
               $("#both_div").show();
           }
           return false;
       }
       jQuery(document).ready(function ($) {
           
       });
   </script>
@endsection
