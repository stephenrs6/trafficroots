@extends('layouts.app')
@section('title', 'Custom Ads')
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
                <div class="ibox-title">Create a Custom Ad on {{ $site->site_name }} - {{ $zone->description }}</div>

                <div class="ibox-content">

                <form name="campaign_form" id="campaign_form" class="form-horizontal" role="form" method="POST" action="{{ url('/custom_ad') }}">
		{{ csrf_field() }}
                <input type="hidden" name='handle' id='handle' value="{{ $zone->handle }}">
                <input type="hidden" name='location_type' id="location_type" value="{{ $zone->location_type }}">
                <div id="wizard">
                    <h1>Details</h1>


                        <div class="steps-content" style="background: white">
                            <div class="col-md-12">
                                <div class="ibox float-e-margins">
				    <div class="ibox-content">
                                        <h2 class="text-success"><strong>Basic Information</strong></h2>
                                        <div class="form-group{{ $errors->has('campaign_name') ? ' has-error' : '' }}">
                                            <label for="campaign_name" class="col-md-4 control-label">Campaign Name</label>
                                            <div class="col-md-8">
						<input id="campaign_name" type="text" class="form-control" name="campaign_name" placeholder="Campaign Name" value="{{ old('campaign_name') }}" required autofocus> 
                                                @if ($errors->has('campaign_name'))
                                                <span class="help-block">
                                                        <strong>{{ $errors->first('campaign_name') }}</strong>
                                                </span> 
						@endif
                                            </div>
					</div>
                                        <div class="form-group{{ $errors->has('campaign_weight') ? ' has-error' : '' }}">
                                            <label for="campaign_weight" class="col-md-4 control-label">Campaign Weight</label>
                                            <div class="col-md-8">
						<input id="campaign_weight" type="text" class="form-control" name="campaign_weight" placeholder="Campaign Weight" value="{{ old('campaign_weight') }}" required> 
						<span class="help-block">You have {{ $available }} % of this Zone's Weight available for this new Ad.  Please enter a number between 1 and {{ $available }}</span>
						@if ($errors->has('campaign_weight'))
                                                <span class="help-block">
                                                        <strong>{{ $errors->first('campaign_weight') }}</strong>
                                                </span> 
						@endif
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
                        <div class="instruction">
                          <ul>
                              <li>Combine an image and link in order to make a new creative!</li>
                          </ul>
                        </div>
                          <!-- <div class="text-center image-preview show-icon">
                                <i class="fa fa-camera"></i>
                                <img class="newCampaignImg" src"" alt="Preview Image"/>
                            </div> -->
				    <div class="form-group{{ $errors->has('description') ? ' has-error' : '' }}" style="margin:0;">
                                        <label for="description" class="col-md-3 control-label">
                                          <em class="fa fa-question-circle" data-toggle="tooltip" data-placement="top" title="Add description to unique Image and URL combination"></em>
                                          &nbsp;Description:
                                        </label>
                                        <div class="col-md-9">
                                            <input id="description" type="text" class="form-control" name="description" value="" required autofocus> @if ($errors->has('description'))
                                            <span class="help-block">
                                                <strong>{{ $errors->first('description') }}</strong>
                                            </span> @endif
                                        </div>
				    </div>
                                     <div class="form-group{{ $errors->has('banner_link') ? ' has-error' : '' }}" style="margin:0;">
                                        <label for="banner_link" class="col-md-3 control-label">
                                          <em class="fa fa-question-circle" data-toggle="tooltip" data-placement="top" title="Enter a valid link to your desired Banner Image"></em>
                                          &nbsp;Banner Link:
                                        </label>
                                        <div class="col-md-9">
                                            <input id="banner_link" type="url" class="form-control" name="banner_link" value="" required autofocus> @if ($errors->has('banner_link'))
                                            <span class="help-block">
                                                <strong>{{ $errors->first('banner_link') }}</strong>
                                            </span> @endif
                                        </div>
				    </div>
                                      <div class="form-group{{ $errors->has('description') ? ' has-error' : '' }}" style="margin:0;">
                                        <label for="click_link" class="col-md-3 control-label">
                                          <em class="fa fa-question-circle" data-toggle="tooltip" data-placement="top" title="Add a valid link to your click through destination."></em>
                                          &nbsp;Click Link:
                                        </label>
                                        <div class="col-md-9">
                                            <input id="click_link" type="text" class="form-control" name="click_link" value="" required autofocus> @if ($errors->has('click_link'))
                                            <span class="help-block">
                                                <strong>{{ $errors->first('click_link') }}</strong>
                                            </span> @endif
                                        </div>
				    </div>
                                 
                                        <div class="text-center" style="padding:4px;">
                                            <button class="btn btn-primary" onclick="return addCreative();"><i class="fa fa-plus-square-o"></i>&nbsp;Add Creative</button>
                                            <br />
                                        </div>
                                    <div class="ibox-content" id="creatives">
                                        <h4>Creatives:</h4>
                                    </div>

                            </div>


                    <h1>Overview & Options</h1>

                        <div id="overview" class="step-content">
                            <!-- Overview DIV -->

                            <div id="overview_content" style="padding:3px;">
                            </div>
                          <!--   End Overview Portion     -->

                        <br>
                        <div class="col-md-12">
                                    <div class="ibox float-e-margins">
                                        <div class="ibox-title">
                                            <h2 class="text"><strong style="color: #1AB394;">Configuration</strong></h2>
                                        </div>
                                        <div class="ibox-content" style="overflow:visible;">
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
                                                <label class="col-md-4 control-label">Maximum Impressions
                                                  <em class="fa fa-question-circle" data-toggle="tooltip" data-placement="top" title="Set an Overall Impression Limit for your Campaign - (0 = unlimited)"></em>
                                                </label>
                                                &nbsp;
						<div class="col-md-6">
                                                    <input type="text" id="impression_limit" name="impression_limit" value="0" required>
                                                </div>
                                                    @if ($errors->has('impression_limit'))
                                                    <span class="help-block">
                                                                        <strong>{{ $errors->first('impression_limit') }}</strong>
                                                    </span> 
						    @endif

					     </div>
                                        </div>
                                        </div>
	                                    <div class="ibox float-e-margins">
                                        <div class="ibox-title">
					    <h2 class="text"><strong style="color: #1AB394;">Scheduling Options</strong></h2>
                                                         <em class="fa fa-question-circle pull-right" 
                                                             data-toggle="tooltip" 
                                                             data-placement="top" 
                                                             title="Campaign will begin immediately, or you can optionally control delivery with the schedule dates below."></em>

                                        </div>
                                        <div class="ibox-content" style="overflow:visible;">
				    <div class="form-group">
						<div class="row">
						<label class="col-md-4 control-label">Start Date
                                                         <em class="fa fa-question-circle" 
                                                             data-toggle="tooltip" 
                                                             data-placement="top" 
                                                             title="This field is required."></em>
						</label>
						<div class="col-md-4">
                                                           <input
                                                               type="text"
                                                               name="daterange_start"
							       id="daterange_start"
                                                               value="{{ date('m/d/Y') }}"
                                                               required />
                                                                        <label class="error hide"
                                                                                   for="daterange_start"></label>
						</div></div>
                                                <div class="row">
						<label class="col-md-4 control-label">Optional Ending Date
                                                         <em class="fa fa-question-circle" 
                                                             data-toggle="tooltip" 
                                                             data-placement="top" 
                                                             title="Campaign will pause/end on this date, if specified.  Otherwise it will run until paused or stopped in the Zone Management page."></em>
						</label>
						<div class="col-md-4">
                                                            <input
                                                                   type="text"
                                                                   id="daterange_end"
								   name="daterange_end" />
                                                                    <label class="error hide"
                                                                                   for="daterange_end"></label>
                                                </div>
                                                </div>
					    </div>
                                           
                                        </div>
                                    </div>
                                </div>



                        </div>
                        </div>
                        </form>
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
	reloadMedia();
        $(document).on('hidden.bs.modal', function(){
            reloadMedia();
        });
	
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
					var creatives = 0;
					$(".creative").each(function(){
						creatives++
					});

					if(!creatives){
						alert('Please add at least one Creative! In order to add a creative please fill out the form and then click the ADD CREATIVE button.');

						if (!form.valid()) {
							event.PreventDefault();
							event.stopPropagation();
						}

						return false;
					} else {
						$("#description").prop('required',false);
						$("#banner_link").prop('required',false);
						$("#click_link").prop('required',false);

						form.find(".body:eq(" + priorIndex + ") label.error").remove();
						form.find(".body:eq(" + priorIndex + ") .error").removeClass("error");
						return true;
					}
				}
				
				if (currentIndex == 3) {
					$("#description").prop('required',false);
					$("#banner_link").prop('required',false);
					$("#click_link").prop('required',false);
				}
				
				form.validate().settings.ignore = ":disabled,:hidden";
				return form.valid();
			},
			onStepChanged: function (event, currentIndex, priorIndex){
				updateOverview();
			},
			onFinishing: function (event, currentIndex){
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
		  	onCanceled: function (event, currentIndex){
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

    });
    function checkForm(){
       if($.trim($('#campaign_name').val()) == ''){
           alert('Campaign must have a name!');
           $('#campaign_name').focus();
           return false;
       }
       
       if(confirm("Submit this campaign?")){
           var data = $('#campaign_form').serialize();
           $.post('/custom_ad', data).done(function(result){
           info = JSON.parse(result);
           if(info.result == 'OK'){
               toastr.success("Campaign Created!", function(){
                   setTimeout(function(){ window.location = '/zone_manage/{{ $zone->handle }}'; }, 2000);
               });
               }else{
               toastr.error(info.result);
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
	var myhtml =  '<style>.col-md-3 {text-align: right;}</style><div class="ibox-content" style="overflow:hidden"><h2 class="text-success"><strong>General</strong></h2><div class="row m-t"><div class="form-group"><div class="col-md-3"> <label class="control-label p-t-half">Campaign Name</label></div><div class="col-md-4"><div type="text" value="campaign name" class="form-control" disabled> ' + $('#campaign_name').val() + '</div></div></div></div><div class="row m-t"><div class="form-group"><div class="col-md-3"> <label class="control-label p-t-half">Campaign Weight</label></div><div class="col-md-4"><div type="text" value="campaign weight" class="form-control" disabled> ' + $('#campaign_weight').val() + '</div></div></div></div></div> <br><div class="ibox-content"><h2 class="text-success"><strong>Advanced Targeting</strong></h2><div class="row m-t"><div class="col-md-3"> <label class="control-label p-t-half">Country Targeting</label></div><div class="col-md-4"><div type="text" text="example, example" class="form-control" disabled> ' + $('#countries option:selected').text() + '</div></div></div><div class="row m-t"><div class="col-md-3"> <label class="control-label p-t-half">State Targeting</label></div><div class="col-md-4"><div type="text" text="example, example" class="form-control" disabled> ' + $('#states option:selected').text() + '</div></div></div><div class="row m-t"><div class="col-md-3"> <label class="control-label p-t-half">County Targeting</label></div><div class="col-md-4"> <div type="text" text="example, example" class="form-control" disabled> ' + $('#counties option:selected').text() + ' </div></div></div><div class="row m-t"><div class="col-md-3"> <label class="control-label p-t-half">Platform Targeting</label></div><div class="col-md-4"><div type="text" text="example, example" class="form-control" disabled> ' + $('#platform_targets option:selected').text() + '</div></div></div><div class="row m-t"><div class="col-md-3"> <label class="control-label p-t-half">OS Targeting</label></div><div class="col-md-4"><div type="text" text="example, example" class="form-control" disabled> ' + $('#operating_systems option:selected').text() + '</div></div></div><div class="row m-t"><div class="col-md-3"> <label class="control-label p-t-half">Browser Targeting</label></div><div class="col-md-4"><div type="text" text="example, example" class="form-control" disabled> ' + $('#browser_targets option:selected').text() + '</div></div></div><div class="row m-t"><div class="col-md-3"> <label class="control-label p-t-half">Keyword Targeting</label></div><div class="col-md-4"><div type="text" text="example, example" class="form-control" disabled> ' + $('#keyword_targets').val() + '</div></div></div></div> <style>.col-md-3 {text-align: right;}</style><div class="ibox-content"><h2 class="text-success"><strong>Creatives</strong></h2><div class="row m-t"><div class="col-md-3"> <label class="control-label p-t-half">Number of Creatives</label></div><div class="col-md-4"><div type="text" text="example, example" class="form-control" disabled> ' + creatives + '</div></div><!--ends here -->';

        $('#overview_content').html(myhtml);
    }
    function reloadMedia(){
        var category = 0;
    var location_type = parseInt($('#location_type').val());
            var url = '/getmedia?category=' + category + '&location_type=' + location_type;
            $.getJSON(url, function(data){
                $('#folder_id').html(data.folders);
                $('#link_id').html(data.links);
                $('#media_id').html(data.media);
            });
    }
    function addCreative(){
        var description = $.trim($('#description').val());
        if(description == ''){
            alert("Creative must have a description.");
            $('#description').focus();
            return false;
        }
        var banner_link = $.trim($('#banner_link').val());
        if(banner_link == ''){
            alert("Please enter a valid link to your banner image.");
            $('#banner_link').focus();
            return false;
        }
        var click_link = $.trim($('#click_link').val());
        if(click_link == ''){
            alert("Please enter a valid link to your click through destination.");
            $('#click_link').focus();
            return false;
	}      
        var current_creatives = $('#creatives').html();
	var this_creative = '<div class="row" id="row_' + banner_link + '_' + click_link + '" style="padding:2px;"><div class="col-md-2">&nbsp;</div><div class="col-md-8"><input class="creative" name="creative_' + banner_link + '_' + click_link + '" id="creative_' + banner_link + '_' + click_link + '" type="hidden" value="' + encodeURI(description) + '|' + encodeURI(banner_link) + '|' + encodeURI(click_link) + '"><b>Description: ' + description + '</b> <br /><b>Banner Link: </b> ' + encodeURI(banner_link) + '<br /><b>Click Through Link: </b>' + encodeURI(click_link) + '<br /><a href="' + click_link + '"><img src="' + banner_link + '"></img></a></div><div class="col-md-2"><button class="btn btn-xs btn-danger" onclick="$(this).parent().parent().remove();"><i class="fa fa-remove"></i>&nbsp;Remove</button></div><hr><br/></div><br>';
       $('#creatives').html(current_creatives + this_creative);
       $('#description').val('');
       $('#banner_link').val('');
       $('#click_link').val('');
    return false;
    }

</script>
@endsection
