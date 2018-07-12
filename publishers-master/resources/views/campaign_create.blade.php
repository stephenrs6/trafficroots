@extends('layouts.app')
@section('title', 'Campaigns')
@section('css')
<link rel="stylesheet"
      href="{{ URL::asset('css/plugins/select2/select2.min.css') }}">
<link rel="stylesheet"
      href="{{ URL::asset('css/plugins/chosen/chosen.css') }}">
<link rel="stylesheet"
      href="{{ URL::asset('css/custom.css') }}">

<style>
	#mediaModal > div > button {
		display:none;
	}
</style>
@endsection

@section('js')
<script src="{{ URL::asset('js/plugins/select2/select2.full.min.js') }}"></script>
<script src="{{ URL::asset('js/plugins/chosen/chosen.jquery.js') }}"></script>

@endsection

@section('content')
    @if(Session::has('success'))
        <div class="alert alert-success">
            <h2>{{ Session::get('success') }}</h2>
        </div>
    @endif
<style>


#page-wrapper .container .ibox-content {
    overflow: visible;
}

	#overview_content .row.m-t div > div {
		word-wrap: break-word;
	}

#wizard {
    overflow: visible;
}

.ibox {
    width: 80%;
}

.wizard .content {
    min-height: 100px;
    overflow: visible;
}
.wizard .content > .body {
    width: 100%;
    height: auto;
    padding: 15px;
    position: relative;
}

#keyword_targets {
    margin-top: 10px;
}

#wizard-p-1 h5, h6, small{
    padding-left: 5px;
}

#wizard-p-1 {
    background: white;
 }

#wizard-p-2 .media-selection .createNew {
    padding: 5px;
}

#wizard-p-2 .media-selection {
  margin-top: 20px;
}

#wizard-p-2 .media-selection h3 {
  color: #1c84c6;
}

#wizard-p-2 .media-selection h4 {
    font-size: 15px;
    margin-bottom: 15px;
}

#wizard-p-2 > div.media-selection > div:nth-child(5) {
  border-right: none;}


#media_id, #link_id {
  display:inline;
  width: 80%;
  margin: 10px;
}

#wizard-p-3 {
    background: white;
}

#wizard-p-3 .col-md-12 .col-md-3 {
    text-align: right;
}

#wizard-p-2 > div.media-selection > div:nth-child(4) {
	border-right-color: #fff;
}

#wizard-p-2 > div.media-selection > div:nth-child(4) > div.text-center > button {
  margin: 20px;
}

#wizard-p-2 > div > div:nth-child(5) > div.text-center > button {
  margin: 20px 0;
}

.instruction {
  margin: 0px 30px 25px 30px;
}

.step-content .media-selection .col-xs-12 .chkRadioBtn {
    display: inline-block;
}
	
input, select {
	margin-top: 15px;
}


.image-preview i.fa { display: none; }
.image-previewdiv img { display: block; }

.image-preview.show-icon i.fa { display: inline-block; font-size: 100px; }
.image-preview.show-icon img { display: none; }

#overview .col-md-12 {
    margin-top: 10px;
}

#wizard-p-3 .ibox-content .form-control {
  height: 100%;
  min-height: 32px;
}

@media (max-width: 1100px) {
    .ibox {
        width: 100%;
      }

}

@media (max-width: 768px) {
    #overview_content .ibox-content .col-md-3 {
      text-align: left;
  }
}

@media (max-width: 680px) {
    #page-wrapper {
      padding: 0;
    }

    .wrapper .container .row .col-md-12 {
      padding-left: 0px;
      padding-right: 0px;
    }

    #wizard-p-2 > div.media-selection > div:nth-child(2) {
    	margin-top: 30px;
    }

    .wizard .content .body {
      padding: 15px 0px;
    }

    .steps ul li[role=tab] {
      width: 100%;
    }

}



</style>
<div class="container">
    <div class="row">
        <div class="col-md-12">
            <div class="ibox">
                <div class="ibox-title">Create a Campaign</div>

                <div class="ibox-content">

                <form name="campaign_form" id="campaign_form" class="form-horizontal" role="form" method="POST" action="{{ url('/campaign') }}">
                {{ csrf_field() }}
                <div id="wizard">
                    <h1>Campaign Details</h1>


                        <div class="steps-content" style="background: white">
                            <div class="col-md-12">
                                <div class="ibox float-e-margins">
                                    <div class="ibox-content">
                                        <h2 class="text-success"><strong>Basic Information</strong></h2>
                                        <div class="form-group{{ $errors->has('name') ? ' has-error' : '' }}">
                                            <label for="campaign_name" class="col-md-4 control-label">Campaign Name</label>
                                            <div class="col-md-6">
                                                <input id="campaign_name" type="text" class="form-control" name="campaign_name" placeholder="Campaign Name" value="{{ old('campaign_name') }}" required autofocus> @if ($errors->has('campaign_name'))
                                                <span class="help-block">
                                                                    <strong>{{ $errors->first('campaign_name') }}</strong>
                                                                </span> @endif
                                            </div>
                                        </div>
                                        <div class="form-group{{ $errors->has('campaign_category') ? ' has-error' : '' }}">
                                            <label for="campaign_category" class="col-md-4 control-label">Campaign Category
                                              <em class="fa fa-question-circle" data-toggle="tooltip" data-placement="top" title="Category Must Match for the Campaign/Images/URL"></em>
                                            </label>
                                            <div class="col-md-6">
                                                <select id="campaign_category" class="form-control reload required" name="campaign_category" required>
                                                    <option value="">Choose</option>
                                                    @foreach($categories as $type)
                                                    <option value="{{ $type->id }}">{{$type->category}}</option>
                                                    @endforeach
                                                </select>
                                                @if ($errors->has('campaign_category'))
                                                <span class="help-block">
                                                                    <strong>{{ $errors->first('campaign_category') }}</strong>
                                                                </span> @endif
                                            </div>
                                        </div>
                                        <div class="form-group{{ $errors->has('location_type') ? ' has-error' : '' }}">
                                            <label for="location_type" class="col-md-4 control-label">Location Type
                                              <em class="fa fa-question-circle" data-toggle="tooltip" data-placement="top" title="Specifies the ad location on a Webpage, determined by the Image Dimensions"></em>
                                            </label>
                                            <div class="col-md-6">
                                                <select id="location_type" class="form-control reload" name="location_type" required>
                                                    <option value="">Choose</option>
                                                    @foreach($location_types as $type)
                                                    <option value="{{ $type->id }}">{{$type->description}} - {{$type->width}}x{{$type->height}}</option>
                                                    @endforeach
                                                </select>
                                                @if ($errors->has('location_type'))
                                                <span class="help-block">
                                                                    <strong>{{ $errors->first('location_type') }}</strong>
                                                                </span> @endif
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                        </div>


                    <h1>Advanced Targeting</h1>
                        <div class="step-content">
                            <h5>Target an advertisement based on a specific geographical region, area or location. Geo-targeting is done based on the IP address of the visitor.</h5>
                            <div class="col-md-12">
                                <h6>Site Targeting - Hold Ctrl to Select Multiple Themes</h6>
				<select id="themes" name="themes[]" class="chosen-select form-control" multiple>
                                    <option value="0" selected>All Sites</option>
                                    @foreach($themes as $theme)
                                    <option value="{{ $theme->id }}">{{ $theme->theme }} </option>
                                    @endforeach
                                </select>
                            </div>
			    <div class="col-md-12">
                                <h6>Country / Geo Targeting - Hold Ctrl to Select Multiple Countries</h6>
                                <select id="countries" name="countries[]" class="chosen-select form-control" multiple>
                                    {!! $countries !!}
                                </select>
                            </div>
                            <div class="col-md-12">
                                <h6>State Targeting - Hold Ctrl to Select Multiple States</h6>
                                <select id="states" name="states[]" class="chosen-select form-control state-control" multiple>
                                    {!! $states !!}
                                </select>
                            </div>
                            <div class="col-md-6">
                                <h6>Platform Targeting - Hold Ctrl to Select Multiple Platforms</h6>
                                <select name="platform_targets[]" id="platform_targets" class="chosen-select form-control" multiple>
                                    {!! $platforms !!}
                                </select>
                            </div>
                            <div class="col-md-6">
                            <h6>County Targeting - Hold Ctrl to Select Multiple Counties</h6>
                                <select name="counties[]" id="counties" class="chosen-select form-control counties" multiple>
                                {!! $counties !!}
                                </select>
                            </div>
                            <div class="col-md-6">
                                <br />
                                <h6>OS Targeting - Hold Ctrl to Select Multiple Operating Systems</h6>
                                <select id="operating_systems" name="operating_systems[]" class="chosen-select form-control" multiple>
                                    {!! $os_targets !!}
                                </select>
                            </div>
                            <div class="col-md-6">
                                <br />
                                <h6>Browser Targeting - Hold Ctrl to Select Multiple Browser Types</h6>
                                <select id="browser_targets" name="browser_targets[]" class="chosen-select form-control" multiple>
                                    {!! $browser_targets !!}
                                </select>
                            </div>
                            <div class="col-md-12" style="margin-top:25px">
                                <div>
                                    <em class="fa fa-question-circle" data-toggle="tooltip" data-placement="top" title="Search terms or phrases targeted by the advertising campaign"></em>
                                    <h5 style="display:inline">Keyword Targeting &nbsp;<span style="font:italic 100 12px opensans">(Optional)
                                    </span></h5>
                                </div>
                                <input name="keyword_targets" id="keyword_targets" class="form-control" type="text" placeholder="Use commas to seperate" value="">
                            </div>
                        </div>


                    <h1>Creatives</h1>
                    <div class="step-content" style="background: white;">
                        <h2 class="text-success"><strong>Add Creatives</strong></h2>
                                <div class="media-selection">
                                    <div class="col-xs-12 col-md-6 b-r">
                                      <h3>Step 1)</h3>
                                        <div class="col-xs-12 form-group{{ $errors->has('media_id') ? ' has-error' : '' }}" style="float:none;margin-bottom:0;">
                                            <p><h4>Select Existing or Uploaded Image&nbsp;&nbsp;<i class="fa fa-camera"></i></h4></p>
                                                <div class="form-group col-xs-12 mediaOptions">
                                                    <em class="fa fa-question-circle" data-toggle="tooltip" data-placement="top" title="Choose an Image from Corresponding Library Category" style="display:inline;"></em>
                                                    <select id="media_id" name="media_id" class="form-control" required>
                                                        <option value="">Choose</option>
                                                    </select>
                                                    @if ($errors->has('media_id'))
                                                        <span class="help-block">
                                                            <strong>{{ $errors->first('media_id') }}</strong>
                                                        </span> @endif
                                                </div>
                                        </div>
                                        <div class="createNew">
                                            <h4 for="imgCreateNew">Add New Image to Library</h4>
                                            <button id="btnImage" type="button" class="btn btn-xs btn-primary" data-toggle="modal" data-target="#addMedia"><i class="fa fa-plus-square-o"></i>&nbsp;&nbsp;Add Image</button>
                                        </div>
                                    </div>
                                    <div class="col-xs-12 col-md-6">
                                      <h3>Step 2)</h3>
                                        <div class="col-xs-12 form-group{{ $errors->has('link_id') ? ' has-error' : '' }}" style="float:none;margin-bottom:22px">
                                                <p><h4>Select Existing or Uploaded URL  &nbsp;<i class="fa fa-link"></i></h4></p>
                                                <div class="col-xs-12">
                                                  <em class="fa fa-question-circle" data-toggle="tooltip" data-placement="top" title="Choose a Link from Corresponding Library Category" style="display:inline;"></em>
                                                    <select id="link_id" name="link_id" class="form-control" required>
                                                        <option value="">Choose</option>
                                                    </select>
                                                    @if ($errors->has('link_id'))
                                                    <span class="help-block">
                                                        <strong>{{ $errors->first('link_id') }}</strong>
                                                    </span> @endif
                                                </div>
                                        </div>
                                        <div class="createNew">
                                             <h4 for="linkCreateNew">Add New URL To Library</h4>
											 <button type="button" class="btn btn-xs btn-primary" data-toggle="modal" data-target="#addLink"><i class="fa fa-plus-square-o"></i>&nbsp;&nbsp;Add URL&nbsp;&nbsp;&nbsp;&nbsp;</button>
                                        </div>
                                    </div>
                                    <div class="clearfix"></div>

                                <div class="col-xs-12 b-r" style="margin-top: 40px;">
                                  <h3>Step 3)</h3>
                                    <div class="form-group{{ $errors->has('description') ? ' has-error' : '' }}" style="margin:0;">
                                        <h4>Combine Image and URL</h4>
                                        <div class="instruction">
                                          <ul>
											  <li>Multiple Creatives can be added per Campaign!</li>
											  <li>To add a new Creative please repeat <strong>Steps 1-3</strong> in the Creatives tab.</li>
                                          </ul>
                                        </div>
                                        <label for="description" class="col-md-3 control-label">
                                          <em class="fa fa-question-circle" data-toggle="tooltip" data-placement="top" title="Add description to unique Image and URL combination"></em>
                                          &nbsp;Creative Name:
                                        </label>
                                        <div class="col-md-6">
                                            <input id="description" type="text" class="form-control" name="description" value="" required> @if ($errors->has('description'))
                                            <span class="help-block">
                                                <strong>{{ $errors->first('description') }}</strong>
                                            </span> @endif
                                        </div>
                                    </div>
                                        <div class="text-center" style="padding:4px;">
                                            <button class="btn btn-xs btn-primary" onclick="return addCreative();"><i class="fa fa-plus-square-o"></i>&nbsp;Add Creative</button>
                                            <br />
                                        </div>
                                    </div>
                                    <div class="ibox-content" id="creatives">
                                        <h4>Creatives:</h4>
                                    </div>

                            </div>
                    </div>


                    <h1>Overview & Pricing</h1>

                        <div id="overview" class="step-content">
                            <!-- Overview DIV -->

                            <div id="overview_content" style="padding:3px;">
                            </div>
                          <!--   End Overview Portion     -->

                        <br>
                        <div class="col-md-12">
                                    <div class="ibox float-e-margins">
                                        <div class="ibox-title">
                                            <h2 class="text"><strong style="color: #1AB394;">Configuration & Pricing</strong></h2>
                                        </div>
                                        <div class="ibox-content" style="overflow:visible;">
                                            <div class="form-group{{ $errors->has('campaign_type') ? ' has-error' : '' }}">
                                              <label for="campaign_type" class="col-md-4 control-label">Pricing Model
                                                  <em class="fa fa-question-circle" data-toggle="tooltip" data-placement="top" title="CPM (Cost Per Mille)&nbsp;&nbsp;&nbsp; CPC (Cost Per Click)"></em>
                                              </label>
                                              <div class="col-md-6">
                                                    <select id="campaign_type" class="form-control" name="campaign_type" required>
                                                        <option value="">Choose</option>
                                                        @foreach($campaign_types as $type)
															<option value="{{ $type->id }}">{{$type->campaign_type}}</option>
														@endforeach
                                                    </select>
													@if ($errors->has('campaign_type'))
													<span class="help-block">
												  		<strong>{{ $errors->first('campaign_type') }}</strong>
												  	</span> @endif
												</div>
                                               </div>
                                            </div>
                                            <div class="form-group{{ $errors->has('impression_capping') ? ' has-error' : '' }}">
                                                <label for="frequency_capping" class="col-md-4 control-label">Frequency Capping
                                                  <em class="fa fa-question-circle" data-toggle="tooltip" data-placement="top" title="Restrict (cap) the number of times (frequency) a specific visitor to a website is shown a particular ad"></em>
                                                </label>
                                                <div class="col-md-6">
                                                    <select id="frequency_capping" class="form-control" name="frequency_capping" required>
                                                        <option value="0">Disabled</option>
                                                        <option value="1">1 Impression Per 24 Hours</option>
                                                        <option value="2">2 Impressions Per 24 Hours</option>
                                                        <option value="3">3 Impressions Per 24 Hours</option>
                                                        <option value="4">4 Impressions Per 24 Hours</option>
                                                        <option value="5">5 Impressions Per 24 Hours</option>
                                                    </select>
                                                    @if ($errors->has('impression_capping'))
                                                    <span class="help-block">
                                                                        <strong>{{ $errors->first('impression_capping') }}</strong>
                                                                    </span> @endif
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <label class="col-md-4 control-label">Max Daily Budget
                                                  <em class="fa fa-question-circle" data-toggle="tooltip" data-placement="top" title="Set a Maximum Daily Budget for your Campaign"></em>
                                                </label>
                                                &nbsp;
                                                <div class="col-md-6">
                                                    <input type="number" class="form-control" id="daily_budget" name="daily_budget" required>
                                                </div>
                                            </div>
                                            <div class="form-group">
												<div id="bid-tips"></div>
												<label class="col-md-4 control-label">Place Your Bid
													<em class="fa fa-question-circle" data-toggle="tooltip" data-placement="top" title="Amount paid for a click, or one thousand impressions"></em>
												</label>
												<div class="col-md-6">
													<input type="number" id="bid" class="bid form-control" name="bid" placeholder="0.00" min="0" required>
													<label class="error hide" for="bid"></label>
												</div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                        </div>
                        </div>
                        </form>
                        <br />
                        <div class="ibox-content" id="mediaModal">
							<!--<h5>Add New Media or Links Here:</h5>-->
                            <div>@include('media_upload')</div>
                            <br />
                            <div>@include('link_upload')</div>
                            <h3>Campaign:</h3>
                            <div class="well">
                                <ul>
                                    <li>A Trafficroots Campaign is a targeted advertising plan that consists of at least one Creative.</li>
                                    <li>Campaigns target a specific Location Type and all Creatives must conform.</li>
                                    <li>All Creatives and their destination Links must conform to the specified Category.</li>
                                    <li>We offer Cost Per Click (CPC) and Cost Per Milli (CPM) Campaign Types. </li>
                                </ul>
                            </div>
                        </div>
						<div class="hidden">
							<form name="bid_form" id="bid_form" role="form" class="form-horizontal" action="/view_bid" method="POST">
								{{ csrf_field() }}
								<input type="text" id="bid_status" class="form-control" name="bid_status" value="" required>
								<input type="text" id="camp_category" name="camp_category" value="">
								<input type="text" id="camp_location" name="camp_location" value="">
								<input type="text" id="camp_type" name="camp_type" value="">
							</form>
						</div>
            </div>
        </div>
    </div>
</div>
<script src="{{ URL::asset('js/plugins/sweetalert/sweetalert.min.js') }}"></script>
<script src="{{ URL::asset('js/plugins/flot/jquery.flot.js') }}"></script>
<script src="{{ URL::asset('js/plugins/flot/jquery.flot.tooltip.min.js') }}"></script>
<script src="{{ URL::asset('js/plugins/staps/jquery.validate.min.js') }}"></script>

<script type="text/javascript">
    //$('[multiple]').chosen();
    jQuery(document).ready(function($){
        $('#campaign_name').focus();
        $(document).on('hidden.bs.modal', function(){
            reloadMedia();
        });
		
		$('#image_category_id').prop('disabled', true);
		$('#link_category_id').prop('disabled', true);
		$('#location_type_id').prop('disabled', true);
		
		var form = $("#campaign_form");
		form.validate({
			errorPlacement: function errorPlacement(error, element) { element.before(error); }
		});
		
        form.children("div").steps({
			transitionEffect: "fade",
        	autoFocus: true,
			onStepChanging: function (event, currentIndex, priorIndex) {
				
				
				// Allways allow previous action even if the current form is not valid!
				if (currentIndex > priorIndex) {
					return true;
				}
				
				// Needed in some cases if the user went back (clean up)
				if (currentIndex < priorIndex) {
					// To remove error styles
					form.find(".body:eq(" + priorIndex + ") label.error").remove();
					form.find(".body:eq(" + priorIndex + ") .error").removeClass("error");
				}
				
				if (currentIndex == 2) {
					if (($("#media_id option:selected").val() > 0) && 
						($("#link_id option:selected").val() > 0)) {
						//alert("validation passed");
						if ($('input.creative').length === 0){
							alert('You have not yet added a creative for the selected image and link. Please give the creative a description and click on the "Add Creative" button in STEP 3.');
							if (!form.valid()) {
								event.PreventDefault();
								event.stopPropagation();
							}
							return false;
						}
					}
				}
				
				form.validate().settings.ignore = ":disabled,:hidden";
				return form.valid();
			},
			onStepChanged: function (event, currentIndex, priorIndex) {
				updateOverview();
			},
			onFinishing: function (event, currentIndex) {
			   form.validate().settings.ignore = ":disabled";
				if (form.valid()) {
					return checkForm();
				}
			},
			onFinished: function (event, currentIndex) {
				$('#campaign_form').submit(function(){
					alert("Submitted");
				});
		  	},
		  	onCanceled: function (event, currentIndex) {
				swal({
					title: "Cancel Campaign",
					text: "Are you sure you want to cancel this campaign?",
					icon: "warning",
					buttons: true,
					dangerMode: true,
				}).then((cancel) => {
					if (cancel) {
						window.location.href = "campaigns";
					}
				});
			}
        });
        @if($user->allow_folders)
        $('#folder_id').change(function(){
    	});
    	@endif
		
		$("#campaign_category").change(function (e) {
			var getCampaign = '"' + $("#campaign_category option:selected").text() + '"';
			$("#image_category_id option:contains(" + getCampaign + ")").attr('selected', 'selected');
			$("#link_category_id option:contains(" + getCampaign + ")").attr('selected', 'selected');
			var getSelectedOption = $(this).val();
			$("#camp_category").val(getSelectedOption);
		});
		
		$("#location_type").change(function (e) {
			var getLoctionType = $("#location_type option:selected").text();
			var getStringLocType = '"' + getLoctionType.substr(0, getLoctionType.indexOf(' -')) + '"'; 
			$("#location_type_id option:contains(" + getStringLocType + ")").attr('selected', 'selected');
			var getSelectedOption = $(this).val();
			$("#camp_location").val(getSelectedOption);
		});	
		
		$("#campaign_type").change(function () {
			var getSelectedOption = $(this).val();
			$("#camp_type").val(getSelectedOption);
			var bidValue = $("#bid").val();
			if(bidValue) {
				$("#bid_status").val(bidValue);
				$("#bid_status").submit();
			}
		});
		
        $('.state-control').change(function(){
            var url = "{{ url('/load_counties') }}";
            var mydata = $("#campaign_form").serialize();
            $.post(url, mydata)
        .done(function (response) {
                    $('.counties').html(response);
                })
                .fail(function (response) {
                    toastr.error(response);
                });       });
    $('.reload').change(function($){
           reloadMedia();
        });
		
		$('#bid').keypress(function(event){	
			if (event.keyCode === 10 || event.keyCode === 13){
				event.preventDefault();	
			} 	
		});
		
	var delay = (function(){
	  var timer = 0;
	  return function(callback, ms){
		clearTimeout (timer);
		timer = setTimeout(callback, ms);
	  };
	})();
		
	$('#bid').keydown(function(event){
		if( $('#campaign_type').val() ) {
			delay(function(){
			  	var bidValue = $("#bid").val();
				$("#bid_status").val(bidValue);
				$("#bid_status").submit();
			}, 1000 );
		} else {
			alert("A Pricing Model is needed first in order to give the current bid status.");
		}
	});
		
        if ($("input#websiteUrl").length) {
            $("input#websiteUrl").change(function(){
                var linkurl = $("#websiteUrl").val();

                if (linkurl === "") {
                    $("#urlLink").attr('href', "#");
                    $("#urlLink").removeAttr('target', '_blank');
                } else {
                    $("#urlLink").attr('href', "http://" + linkurl);
                    $("#urlLink").attr('target', '_blank');
                }
            });
        }
		$('[data-toggle="tooltip"]').tooltip();

		$('#addMedia .btn-primary').click(function( event ) {
			$("#image_category_id").prop("disabled", false);
			$("#location_type_id").prop("disabled", false); 
		});	

		$('#addLink .btn-primary').click(function( event ) {
			$("#link_category_id").prop("disabled", false); 
		});	
    });
	
	$('form').submit(function() {
		$("#image_category_id").prop('disabled', true);
		$("#link_category_id").prop('disabled', true);
		$("#location_type_id").prop('disabled', true);
	});
	
	$('#bid_form').submit(function(event) {
		event.preventDefault;
		    // Get form
        var form = $('#bid_form')[0];
        // Create an FormData object
        var data = new FormData(form);

        $.ajax({
            type: "POST",
            url: "{{ url('/view_bid') }}",
            data: data,
            processData: false,
            contentType: false,
            cache: true,
            timeout: 600000,
            success: function (response) {
				if(response.bid_class == 'success') toastr.info(response.bid_range, "Bid Status");
				if(response.bid_class == 'info') toastr.info(response.bid_range, "Bid Status");
				if(response.bid_class == 'warning') toastr.warning(response.bid_range, "Bid Status");
				if(response.bid_class == 'danger') toastr.error(response.bid_range, "Bid Status");
            },
            error: function (e) {
            }
       });
		return false;
	});
	
    function checkForm(){
       if($.trim($('#campaign_name').val()) == ''){
           alert('Campaign must have a name!');
           $('#campaign_name').focus();
           return false;
       }
       var creatives = 0;
       $(".creative").each(function(){
           creatives++
       });
       if(!creatives){
           alert('Please add at least one Creative!');
       return false;
       }
       if(confirm("Submit this campaign?")){
           var data = $('#campaign_form').serialize();
           $.post('/campaign', data).done(function(result){
           info = JSON.parse(result);
           if(info.result == 'OK'){
               toastr.success("Campaign Created!", function(){
                   setTimeout(function(){ window.location = '/campaigns'; }, 2000);
               });
               }else{
               toastr.error(info);
               }
       });
       }
       return true;
    }
    function updateOverview(){
        var creatives = 0;
        var newPreviewImg = $(".newCampaignImg").attr("src");
        $(".creative").each(function(){
            creatives ++;
        });
        var myhtml =  '<style>.col-md-3{text-align:right}</style><div class=ibox-content style=overflow:hidden><h2 class=text-success><strong>General</strong></h2><div class="m-t row"><div class=col-md-3><label class="control-label p-t-half">Campaign Name</label></div><div class=col-md-4><div class=form-control disabled type=text value="campaign name">' + $('#campaign_name').val() + '</div></div></div><div class="m-t row"><div class=col-md-3><label class="control-label p-t-half">Campaign Category</label></div><div class=col-md-4><div class=form-control disabled type=text value="campaign category">' + $('#campaign_category option:selected').text() + '</div></div></div><div class="m-t row"><div class=col-md-3><label class="control-label p-t-half">Location Type</label></div><div class=col-md-4><div class=form-control disabled type=text value="campaign category">' + $('#location_type option:selected').text() + '</div></div></div></div><br><div class=ibox-content><h2 class=text-success><strong>Advanced Targeting</strong></h2><div class="m-t row"><div class=col-md-3><label class="control-label p-t-half">Country Targeting</label></div><div class=col-md-4><div class=form-control disabled type=text text="example, example">' + $('#countries option:selected').text() + '</div></div></div><div class="m-t row"><div class=col-md-3><label class="control-label p-t-half">State Targeting</label></div><div class=col-md-4><div class=form-control disabled type=text text="example, example">' + $('#states option:selected').text() + '</div></div></div><div class="m-t row"><div class=col-md-3><label class="control-label p-t-half">County Targeting</label></div><div class=col-md-4><div class=form-control disabled type=text text="example, example">' + $('#counties option:selected').text() + '</div></div></div><div class="m-t row"><div class=col-md-3><label class="control-label p-t-half">Platform Targeting</label></div><div class=col-md-4><div class=form-control disabled type=text text="example, example">' + $('#platform_targets option:selected').text() + '</div></div></div><div class="m-t row"><div class=col-md-3><label class="control-label p-t-half">OS Targeting</label></div><div class=col-md-4><div class=form-control disabled type=text text="example, example">' + $('#operating_systems option:selected').text() + '</div></div></div><div class="m-t row"><div class=col-md-3><label class="control-label p-t-half">Browser Targeting</label></div><div class=col-md-4><div class=form-control disabled type=text text="example, example">' + $('#browser_targets option:selected').text() + '</div></div></div><div class="m-t row"><div class=col-md-3><label class="control-label p-t-half">Keyword Targeting</label></div><div class=col-md-4><div class=form-control disabled type=text text="example, example">' + $('#keyword_targets').val() + '</div></div></div></div><style>.col-md-3{text-align:right}</style><div class=ibox-content><h2 class=text-success><strong>Creatives</strong></h2><div class="m-t row"><div class=col-md-3><label class="control-label p-t-half">Number of Creatives</label></div><div class=col-md-4><div class=form-control disabled type=text text="example, example">' + creatives + '</div></div><!--ends here -->';

        $('#overview_content').html(myhtml);
    }
    function reloadMedia(){
        var category = parseInt($('#campaign_category').val());
    var location_type = parseInt($('#location_type').val());
        if(category && location_type){
            var url = '/getmedia?category=' + category + '&location_type=' + location_type;
            $.getJSON(url, function(data){
                $('#folder_id').html(data.folders);
                $('#link_id').html(data.links);
                $('#media_id').html(data.media);
            });
        }else{
            $('#folder_id').html("<option value=''>Choose</option>");
            $('#link_id').html("<option value=''>Choose</option>");
            $('#media_id').html("<option value=''>Choose</option>");
        }
    }
	
    function addCreative(){
        var description = $.trim($('#description').val());
        if(description == ''){
            alert("Creative must have a description.");
            $('#description').focus();
            return false;
		}

        var media_id = parseInt($('#media_id').val());
            if(!media_id || isNaN(media_id)){
            alert("Please select a media image.");
            $('#media_id').focus();
            return false;
        }
        var media_option = $('#media_id option:selected').text();
        var link_id = parseInt($('#link_id').val());
        if(!link_id || isNaN(link_id)){
            alert("Please select a link for this creative.");
            $('#link_id').focus();
            return false;
        }
        var link_option = $('#link_id option:selected').text();
        var current_creatives = $('#creatives').html();
        if($('#creative_' + media_id + '_' + link_id).length){
            alert("This creative already exists.");
            return false;
		}
        var this_creative = '<div class="row" id="row_' + media_id + '_' + link_id + '" style="padding:2px;"><div class="col-md-3">&nbsp;</div><div class="col-md-6"><input class="creative" name="creative_' + media_id + '_' + link_id + '" id="creative_' + media_id + '_' + link_id + '" type="hidden" value="' + description + '">' + description + ':   ' + media_option + ' - ' + link_option + '</div><div class="col-md-3"><button class="btn btn-xs btn-danger" onclick="$(this).parent().parent().remove();"><i class="fa fa-remove"></i>&nbsp;Remove</button></div></div>';
       $('#creatives').html(current_creatives + this_creative);
    return false;
    }

</script>
@endsection
