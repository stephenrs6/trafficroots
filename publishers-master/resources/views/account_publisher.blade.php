@extends('layouts.app')

@section('title','- Advertisers')

@section('content')
    @if(Session::has('success'))
        <div id="alert_div" class="alert alert-success">
            <h4>{{ Session::get('success') }}</h4>
        </div>
    @endif
    @if (count($errors) > 0)
        <div class="alert alert-danger">
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif
<div class="container">
    <div class="row">
        <div class="col-md-12">
            <div class="ibox">
                        <div class="ibox-title" id="acct_heading">Publisher Account</div>
                        <div class="ibox-content" id="account_div">
                            
                            @if(is_array($bank))
                            <div class="row">
                            <div class="col-md-3"><strong>Balance:</strong></div><div class="col-md-9"><strong>$</strong>&nbsp;{{ $bank[0]->running_balance }}</div>
                            </div>
                            <div class="row">
                            <div class="col-md-3"><strong>Last Transaction:</strong></div><div class="col-md-9"><strong>$</strong>&nbsp;{{ $bank[0]->transaction_amount }}</div>
                            </div>
                            <div class="row">
                            <div class="col-md-3"><strong>Last Transaction Time:</strong></div><div class="col-md-9">{{ Carbon\Carbon::parse($bank[0]->created_at)->toDayDateTimeString() }}</div>
                            </div>
                            @else
                            <h3>No Account Defined</h3>
                            @endif
                            
                        </div>
            </div>

        </div>
    </div>
</div>

<!-- Modal -->
<div id="myModal" class="modal fade" role="dialog">
  <div class="modal-dialog modal-lg">

    <!-- Modal content-->
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h4 class="modal-title" id="mytitle">Preview</h4>
      </div>
      <div class="modal-body" id="mybody">
       <p>Content</p>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
      </div>
    </div>

  </div>
</div>
<script type="text/javascript">
    jQuery(document).ready(function ($) {
        // $.noConflict();
        $('.camp_row').click(function(){
            var str =  $(this).attr('id');
            var res = str.split("_");
            var url = '/manage_campaign/' + res[2];
            window.location.assign(url);
        });
        $('.camp-stats').click(function(){
            var str =  $(this).attr('id');
            var res = str.split("_");
            var url = '/stats/site/' + res[2] + '/1';
            window.location.assign(url);
        }); 
        $('.camp_row').hover(function() {
            $(this).css('cursor','pointer');
        });
        $('[data-toggle="popover"]').popover({
            html: true,
        });
        $('.tr-iframe').click(function(){
            var str =  $(this).attr('id');
            var res = str.split("_");
            var url = 'https://publishers.trafficroots.com' + res[4];
            $('#mybody').html('<iframe width="100%" height="100%" frameborder="0" src="' + url + '"></iframe>');
            $('#mybody').height(res[3]);
            $('#mybody').width(res[2]);
        });
        if($('#alert_div'))
        {
            $('#alert_div').fadeOut(1600, function(){

             });
        }
    });

</script>
@endsection
