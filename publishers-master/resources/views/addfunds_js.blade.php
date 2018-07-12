@extends('layouts.app')
@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-8 col-md-offset-2">
            <div class="ibox float-e-margins">
                @if ($message = Session::get('success'))
                <div class="custom-alerts alert alert-success fade in">
                    <button type="button" class="close" data-dismiss="alert" aria-hidden="true"></button>
                    {!! $message !!}
                </div>
                <?php Session::forget('success');?>
                @endif
                @if ($message = Session::get('error'))
                <div class="custom-alerts alert alert-danger fade in">
                    <button type="button" class="close" data-dismiss="alert" aria-hidden="true"></button>
                    {!! $message !!}
                </div>
                <?php Session::forget('error');?>
                @endif
                <div class="ibox-title"><h1>Add funds with a Credit Card</h1></div>
                <div class="ibox-content">
                       <div><p>Your current balance is $ {{ $balance }}</p></div>
                        Amount to Deposit:<br />
                        @if($amount)
                        ${{ number_format($amount,2) }}<br /><br />
                        <button
                            id="mybutton"
                            class="velocity-button"
                            data-description="{{ $user->name }} - Traffic Roots Deposit"
                            data-invoice-num="{{ $user_invoice }}"
                            data-amount="{{ number_format($amount,2) }}"
                            data-callback-function="onPaymentCompletion"
                            data-merchant-name="Traffic Roots"
                            data-terminal-profile-id="6830">
                            <i class="fa fa-money"></i> Add Funds
                        </button> 
                        <input type="hidden" name="invoice" id="invoice" value="{{ $user_invoice }}">
                        {{ csrf_field() }}  
                        @else
                            <p>Minimum deposit is $250.00</p>
                            <input type="text" name="amount" id="amount" value="250"><br /><br />
                            <button id="mybutton" class="btn btn-outline btn-primary dim" onclick="return checkDeposit();">Continue</button>
                        @endif              
                </div>
            </div>
        </div>
    </div>
</div>
<script src="https://api.nabcommerce.com/1.3/button.js"></script>
<script type="text/javascript">
    $(document).ready(function(){
	    $('.nav-click').removeClass("active");
	    $('#nav_profile').addClass("active");       
    });
    function onPaymentCompletion(response){
        if(!response) {
		console.log('User Cancelled');
		return false;
	}
              console.log(JSON.stringify(response));
			                
	var poststring = '_token={{ csrf_token() }}&invoice={{ $user_invoice }}&Status=' + response.Status + '&StatusCode=' + response.StatusCode + '&StatusMessage=' + response.StatusMessage + '&TransactionId=' + response.TransactionId + '&CaptureState=' + response.CaptureState + '&TransactionState=' + response.TransactionState + '&Amount=' + response.Amount + '&CardType=' + response.CardType + '&ApprovalCode=' + response.ApprovalCode + '&MaskedPAN=' + response.MaskedPAN + '&PaymentAccountDataToken=' + response.PaymentAccountDataToken;
	var url = "{{ url('/deposit') }}";
	$.post(url,poststring, function(data){
		alert(data);
	});
	window.location = '/profile';            
    }
    function checkDeposit(){
	var amount = $("#amount").val();
	if(amount >= 250){
	    window.location = window.location.href = '?deposit=' + amount;
	    return false;
	}else{
	    alert("Minimum deposit is $250.00");
	    return false;
	}

    }
    </script>
@endsection
