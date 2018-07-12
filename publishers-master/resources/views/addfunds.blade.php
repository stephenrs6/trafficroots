@extends('layouts.app')
@section('title', '- Add Funds!')
@section('content')
<script src="https://api.cert.nabcommerce.com/1.2/post.js" type="text/javascript"></script>
<script src="https://cdn.jsdelivr.net/clipboard.js/1.5.3/clipboard.min.js"></script>
<script src='https://www.google.com/recaptcha/api.js'></script>
<div class="container">
    <div class="row">
		<div class="panel panel-body">
            <div class="p-title">
				<h4>Deposit Funds</h4>
			</div>
            <div class="ibox-content">
            	<div class="row" id="container">
            <div id="errors"></div>
            <form id="payment" onsubmit="return checkForm(this);">
            <div class="col-md-6">
       <div class="control-group">
            <label class="control-label" for="amount">Amount</label>
            <div class="controls">
                <input class="" id="amount" size="30" type="text" value="100.00" autofocus equired/>
            </div>
       </div>
       <div class="control-group">
           <label class="control-label" for="name">Name</label>
           <div class="controls">
               <input class="" id="name" size="30" type="text" value="{{ $user->name }}" required/>
           </div>
       </div>
       <div class="control-group">
           <label class="control-label" for="street">Street</label>
           <div class="controls">
               <input class="" id="street" size="30" type="text" value="{{ $user->addr }}" required/>
           </div>
       </div>
       <div class="control-group">
           <label class="control-label" for="city">City</label>
           <div class="controls">
               <input class="" id="city" size="30" type="text" value="{{ $user->city }}" required/>
           </div>
       </div>
       <div class="control-group">
           <label class="control-label" for="state">State</label>
           <div class="controls">
               <input class="" id="state" size="30" type="text" maxlength="2" value="{{ $user->state }}" required/>
           </div>
       </div>
       <div class="control-group">
           <label class="control-label" for="billingzip">Zip</label>
           <div class="controls">
               <input class="" id="billingzip" size="30" type="text" maxlength="10" value="{{ $user->zip }}" required/>
           </div>
       </div>
       </div>
       <div class="col-md-6">
       <div class="control-group">
           <label class="control-label" for="email">Email</label>
           <div class="controls">
               <input class="" id="email" size="30" type="email" value="{{ $user->email }}" required/>
           </div>
       </div>
       <div class="control-group">
           <label class="control-label" for="phone">Phone</label>
           <div class="controls">
               <input class="" id="phone" size="30" type="phone" value="{{ $user->phone }}" required/>
           </div>
       </div>
       <div class="control-group">
           <label class="control-label" for="cardtype">Card Type</label>
           <div class="controls">
           <select id="cardtype" required />
           <option value="Visa">Visa</option>
           <option value="MasterCard">MasterCard</option>
           <option value="AmericanExpress">AmericanExpress</option>
           <option value="Discover">Discover</option>
           </select>
           </div>
       </div>
       <div class="control-group">
           <label class="control-label" for="pan">Credit Card Number: </label>
           <div class="controls">
               <input id="pan" type="text" maxlength="16" autocomplete="off" value="" required />
           </div>
       </div>
       <div class="control-group">
          <label class="control-label">Expiry Date: </label>
          <div class="controls">
              <select id="exp-mo" required>
              <option value="01">Jan</option>
              <option value="02">Feb</option>
              <option value="03">Mar</option>
              <option value="04">Apr</option>
              <option value="05">May</option>
              <option value="06">Jun</option>
              <option value="07">Jul</option>
              <option value="08">Aug</option>
              <option value="09">Sep</option>
              <option value="10">Oct</option>
              <option value="11">Nov</option>
              <option value="12">Dec</option>
              </select>
              <select id="exp-year" required>
              <option value="17">2017</option>
              <option value="18">2018</option>
              <option value="19">2019</option>
              <option value="20">2020</option>
              <option value="21">2021</option>
              <option value="22">2022</option>
              </select>
          </div>
       </div>
       <div class="control-group">
           <label class="control-label" for="cvc">CVC: </label>
           <div class="controls">
               <input id="cvc" type="text" maxlength="4" autocomplete="off" required />
           </div>
       </div>
       </div>
       <div class="row">
          <div class="col-md-8 col-md-offset-4">
              <label class="control-label" for="process-payment-btn">&nbsp;</label>
              <div class="control-group">
                  <div class="controls">
                      <div class="g-recaptcha validate" data-sitekey="6LcCXwwUAAAAAO8617hw-277eL5cMAJ5SBsebhWk" ></div>
                      <button class="btn btn-outline btn-primary dim"  id="process-payment-btn" type="submit">Process Payment</button>
                  </div>
              </div>
          </div>
       </div>
  </form>
</div>
			</div>
		</div>
	</div>
</div>
<script type="text/javascript">
    $(document).ready(function(){
       $("#process-payment-btn").click(function() {
           var publicKey = 'x';
   
           var cardInfo = {
               name: $("#name").val(),
               email: $("#email").val(),
               phone: $("#phone").val(),
               street: $("#street").val(),
               city: $("#city").val(),
               state: $("#state").val(),
               cardtype: $("#cardtype").val(),
               number: $("#pan").val(),
               cvc: $("#cvc").val(),
               expMonth: $("#exp-mo").val(),
               expYear: $("#exp-year").val(),
               amount: $("#amount").val(),
               zip: $("#billingzip").val(),
           };
   
           var customerInfo = {
               name: $("#name").val(),
               street: $("#street").val(),
               zip: $("#billingzip").val(),
               city: $("#city").val(),
               state: $("#state").val(),
               email: $("#email").val(),
               phone: $("#phone").val(),
           };
   
           var merchantInfo = {
               merchantName: 'Traffic Roots',
               productDescription: 'Digital Marketing Services',
               productQuantity: '1',
               productPrice: $("#amount").val(),
               currencyCode: 'USD',
               orderId: '{{ $user_invoice }}',
               Logo: 'http://nabvelocity.com/features/hosted-checkout/hosted-payment-form/velocity.png',
           }
   
           var captcha = grecaptcha.getResponse();
   
           Velocity.tokenizeForm(publicKey, captcha, cardInfo, customerInfo, merchantInfo, responseHandler);
           return false;
       });
   
       function responseHandler(result) {
           if (result['code'] == 200) {
               alert(result['text']);
           } else {
               for (var i in result) {
                   $('#errors').append(i + ": " + result[i] + "<br />");
               }
   
               alert($('#errors').text());
           }
       }     
    });
    function onPaymentCompletion(response){
        alert(JSON.stringify(response));
    }
    function checkForm(){
        var amount = $("#amount").val();
        if(amount > 0){
            if(confirm("Depositing $" + amount + " to your account.  OK??")){
                return true;
            }else{
                return false;
            }
        }else{
            alert("Minimum deposit is $100.00");
            return false;
        }

    }
</script>
   <script type="text/javascript">
       jQuery(document).ready(function ($) {
	       $('.nav-click').removeClass("active");
	       $('#nav_profile').addClass("active");
       });
   </script>
@endsection
