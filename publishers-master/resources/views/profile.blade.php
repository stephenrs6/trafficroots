@extends('layouts.app')
@section('title', 'My Profile') 
@section('css')
<style>
	.dataTables_paginate .paginate_button, .dataTables_paginate .paginate_button, .dataTables_paginate .ellipsis {
		 padding: 0px; 
		 border: 0px; 
	}
</style>
@endsection
@section('js')
	<script src="{{ URL::asset('js/plugins/dataTables/datatables.min.js') }}"></script>
@endsection
@section('content')
    @if(Session::has('success'))
        <div class="alert alert-success alert-dismissable">
            <h2>{{ Session::get('success') }}</h2>
        </div>
    @endif
<div class="row">
	<div class="col-md-12">
		<div class="tabs-container">
			<ul id="tabs" class="nav nav-tabs" data-tabs="tabs">
				<li><a id="account_tab" href="#account-tab" data-toggle="tab"><span class="fa fa-user"></span><div>My Profile</div></a></li>
				<li><a id="payout_tab" href="#payout-tab" data-toggle="tab"><span class="fa fa-credit-card"></span><div>Payment Information</div></a></li>
				<li><a id="publisher_tab" href="#publisher-tab" data-toggle="tab"><span class="fa fa-copy"></span><div>Publisher Account</div></a></li>
				<li><a id="adertiser_tab" href="#advertiser-tab" data-toggle="tab"><span class="fa fa-money"></span><div>Advertiser Account</div></a></li>
			</ul>
			<div id="my-tab-content" class="tab-content">
				<div class="tab-pane table-responsive active" id="account-tab">
					<div class="ibox">
						<div class="ibox-content">
							@if($user->status == 0)
							<div class="alert alert-warning">
								<div class="row">
									<div class="col-md-4">
										<h3>Your Attention Is Needed</h3>
									</div>
									<div class="col-md-8">
										<p>Your E-Mail Address Has Not Been Confirmed!</p>
										<a href="/send_confirmation">
											<button class="btn btn-primary" style="white-space: normal;">Click Here To Re-Send Confirmation E-Mail</button>
										</a>
									</div>
								</div>
							</div>
							@endif
							<div class="row">
								<div class="col-xs-12 col-md-6">
									<br>
									<h2 class="text-success" align="left" style="font-weight: bold;">Account Contact</h2>
									<form name="profile_form" id="profile_form" class="form-horizontal" role="form" method="POST" action="update_profile">
									{{ csrf_field() }}
										<div class="form-group{{ $errors->has('name') ? ' has-error' : '' }}">
											<label for="name" class="col-md-4 control-label">Name</label>
											<div class="col-sm-8">
												<input id="name" type="text" class="form-control" name="name" value="{{ $user->name }}" required autofocus>

												@if ($errors->has('name'))
													<span class="help-block">
														<strong>{{ $errors->first('name') }}</strong>
													</span>
												@endif
											</div>
										</div>
										<div class="form-group{{ $errors->has('email') ? ' has-error' : '' }}">
											<label for="email" class="col-sm-4 control-label">Email</label>
											<div class="col-sm-8">
												<input type="email" name="email" class="form-control" id="email" value="{{ $user->email }}" required>                       
												@if ($errors->has('email'))
													<span class="help-block">
														<strong>{{ $errors->first('email') }}</strong>
													</span>
												@endif
											</div>
										</div>
										<div class="form-group{{ $errors->has('phone') ? ' has-error' : '' }}">
											<label for="url" class="col-sm-4 control-label">Mobile Phone</label>
											<div class="col-sm-8">
												<input type="text" name="phone" id="phone" class="form-control" value="{{ $user->phone }}">
												@if ($errors->has('phone'))
													<span class="help-block">
														<strong>{{ $errors->first('phone') }}</strong>
													</span>
												@endif
											</div>
										</div>
										<div class="form-group{{ $errors->has('company') ? ' has-error' : '' }}">
											<label for="company" class="col-sm-4 control-label">Company Name</label>

											<div class="col-sm-8">
												<input id="company" type="text" class="form-control" name="company" value="{{ $user->company }}">

												@if ($errors->has('company_name'))
													<span class="help-block">
														<strong>{{ $errors->first('company_name') }}</strong>
													</span>
												@endif
											</div>
										</div> 

										<h2 class="text-success" align="left" style="font-weight: bold;">Billing Information</h2>
										<div class="form-group{{ $errors->has('addr') ? ' has-error' : '' }}">
											<label for="addr" class="col-sm-4 control-label">Address</label>
											<div class="col-sm-8">
												<input id="addr" type="text" class="form-control" name="addr" value="{{ $user->addr }}" required>

												@if ($errors->has('addr'))
													<span class="help-block">
														<strong>{{ $errors->first('addr') }}</strong>
													</span>
												@endif
											</div>
										</div>
										<div class="form-group{{ $errors->has('addr2') ? ' has-error' : '' }}">
											<label for="addr2" class="col-sm-4 control-label">Address2</label>
											<div class="col-sm-8">
												<input id="addr2" type="text" class="form-control" name="addr2" value="{{ $user->addr2 }}">

												@if ($errors->has('addr2'))
													<span class="help-block">
														<strong>{{ $errors->first('addr2') }}</strong>
													</span>
												@endif
											</div>
										</div>
										<div class="form-group{{ $errors->has('city') ? ' has-error' : '' }}">
											<label for="city" class="col-sm-4 control-label">City</label>
											<div class="col-sm-8">
												<input id="city" type="text" class="form-control" name="city" value="{{ $user->city }}" required>

												@if ($errors->has('city'))
													<span class="help-block">
														<strong>{{ $errors->first('city') }}</strong>
													</span>
												@endif
											</div>
										</div> 
										<div class="form-group{{ $errors->has('state') ? ' has-error' : '' }}">
											<label for="state" class="col-sm-4 control-label">State</label>
											<div class="col-sm-8">
												<input id="state" type="text" class="form-control" name="state" value="{{ $user->state }}" maxlength="2" required>

												@if ($errors->has('state'))
													<span class="help-block">
														<strong>{{ $errors->first('state') }}</strong>
													</span>
												@endif
											</div>
										</div> 
										<div class="form-group{{ $errors->has('zip') ? ' has-error' : '' }}">
											<label for="zip" class="col-sm-4 control-label">Zip/Postal Code</label>
											<div class="col-sm-8">
												<input id="zip" type="text" class="form-control" name="zip" value="{{ $user->zip }}" required>

												@if ($errors->has('zip'))
													<span class="help-block">
														<strong>{{ $errors->first('zip') }}</strong>
													</span>
												@endif
											</div>
										</div> 
										<div class="form-group{{ $errors->has('country') ? ' has-error' : '' }}">
											<label for="country" class="col-sm-4 control-label">Country</label>
											<div class="col-sm-8">
												<select id="country" class="form-control" name="country" required>
												@foreach($countries as $country)
												<option value="{{ $country->id }}"
												@if($country->id == $user->country_code)
												selected
												@endif
												>{{ $country->country_name }}</option>
												@endforeach
												</select>
												@if ($errors->has('country'))
													<span class="help-block">
														<strong>{{ $errors->first('country') }}</strong>
													</span>
												@endif
											</div>
										</div>    
										<div class="form-group text-center">
											<br><br>
											<input class="btn btn-primary btn-lg" type="submit" name="submit" id="submit" />
										</div>
									</form>
								</div>
								<div class="col-xs-12 col-md-6">
									<br>
									<h2 class="text-success" align="left" style="font-weight: bold;">Change Password</h2>
									<form class="form-horizontal" id="changePassword" action="/changepw" method="POST">
                                                                                {{ csrf_field() }}
										<div class="form-group">
											<label align="right" class="col-sm-4 control-label">Existing Password</label>
											<div class="col-sm-8"><input id="mypassword" name="mypassword" 
                                                                                                                     placeholder="Password" class="form-control" type="password" required> 
                                                                                        </div>
										</div>
										<div class="form-group">
											<label align="right" class="col-sm-4 control-label">New Password</label>
											<div class="col-sm-8"><input id="newpass" name="newpass" 
                                                                                                                     type="password" class="form-control" 
                                                                                                                     placeholder="Change password" required>
                                                                                        </div>
										</div>
										<div class="form-group">
											<label align="right" class="col-sm-4 control-label">Confirm Password</label>
											<div class="col-sm-8"><input id="confirm" name="confirm" 
                                                                                                                     type="password" class="form-control"
                                                                                                                     placeholder="Confirm password" required>
                                                                                                             </div>
										</div>
										<br>
										<br>
										<div class="form-group text-center">
											<button type="submit" class="btn btn-primary btn-lg">Submit</button>
										</div>
									</form>
								</div>
							</div>
						</div>
					</div>
				</div>
				<div class="tab-pane table-responsive" id="payout-tab">
					<div class="ibox">
					<div class="ibox-content">
						<br>
						<div class="row">
							<div class="col-md-12">
								<div class="panel no-border">
									<div class="panel-body col-md-6">
										<h2 class="text-success" align="left" style="font-weight: bold;">Payment Information</h2>
										<form name="payment_form" id="payment_form" class="form-horizontal" role="form" method="POST" action="/update_payout">
											<div class="form-group">
												<label class="col-sm-4 control-label">Payment Method</label>    
												<div class="col-sm-8">
													<select name="payment_method" id="payment_method" class="form-control" required>
														<option value="">Select</option>
                                                                                                                @foreach($payment_methods as $method)
														<option value="{{$method->id}}" 
                                                                                                                @if(sizeof($payout_settings) && $payout_settings[0]->payment_method == $method->id)
                                                                                                                selected
                                                                                                                @endif
                                                                                                                >{{$method->description}}</option>
                                                                                                                @endforeach
													</select>
												</div>
											</div>
											<div class="form-group">
												<label class="col-sm-4 control-label">Minimum Payout</label>    
												<div class="col-sm-8">
													<select id="minimum_payout" name="minimum_payout" class="form-control" required>
														<option value="">Select</option>
                                                                                                                @foreach($minimum_payouts as $payout)
														<option value="{{$payout->id}}"
                                                                                                                @if(sizeof($payout_settings) && $payout_settings[0]->minimum_payout == $payout->id)
                                                                                                                selected
                                                                                                                @endif
                                                                                                                >$&nbsp;{{$payout->amount}}</option>
                                                                                                                @endforeach
													</select>
												</div>
											</div>

											<h2 class="text-success" align="left" style="font-weight: bold;">Tax Info</h2>
											<div class="form-group">
												<label class="col-sm-4 control-label">Tax Status</label>    
												<div class="col-sm-8">
													<select name="tax_status" id="tax_status" class="form-control" required>
														<option value = "">Select</option>
                                                                                                                @foreach($tax_status as $tax)
														<option value="{{$tax->id}}"
                                                                                                                @if(sizeof($payout_settings) && $payout_settings[0]->tax_status == $tax->id)
                                                                                                                selected
                                                                                                                @endif

                                                                                                                >{{$tax->description}}</option>
                                                                                                                @endforeach
													</select>
												</div>
											</div>
											<div class="form-group">
												<label class="col-sm-4 control-label">Vat/Tax ID</label>
												<div class="col-sm-8"><input name="tax_id" 
                                                                                                                             id="tax_id" placeholder="Vat/Tax ID" 
                                                                                                                             class="form-control" 
															     type="text"
                                                                                                                             @if(sizeof($payout_settings)) value="{{$payout_settings[0]->tax_id}}"@endif
                                                                                                                             >
                                                                                                </div>
											</div>
											<div class="form-group">
												<label class="col-sm-4 control-label"></label>
												<div class="col-sm-8" align="mid"><input placeholder="Future W9 Form" class="form-control" type="text"></div>    
											</div>
											<br><br>{{csrf_field()}}
											<div class="form-group text-center">
											<button type="submit" class="btn btn-primary btn-lg">Submit</button>
										</div>
										</form>
									</div>
								</div>
							</div>
						</div>
					</div>
					</div>
				</div>
				<div class="tab-pane table-responsive" id="publisher-tab">
					<div class="ibox">
					<div class="ibox-content">
						<br>
						<div class="row">
							<div class="col-md-12">
								<div class="panel no-border">
									<div class="panel-body col-md-5">
										<h2 class="text-success"><strong>Publisher Account Information</strong></h2>
										<table class="table">
											<thead>
												<tr></tr>
												<tr></tr>
												<tr></tr>
												<tr></tr>
												<tr></tr>
											</thead>
											<tbody>
												<tr>
													<td><strong>Account Status</strong></td>
                                                                                                        @if($user->status)
													<td>Confirmed</td>
                                                                                                        @else
                                                                                                        <td>Pending</td>
                                                                                                        @endif
												</tr>
												<tr>
													<td><strong>Total Unpaid Earnings</strong></td>
													<td>$&nbsp;{{$current_earnings}}</td>
												</tr>
											</tbody>
										</table>
									</div>
								</div>
							</div>
						</div>
						<br>
						<div class="row">
							<div class="col-lg-12">
								<div class="ibox">
								<div class="panel panel-default">
									<h4 class="p-title">Unpaid Earnings by Site</h4>
									@if($pub)
									<div class="ibox-content tableSearchOnly">
										<table class="tablesaw tablesaw-stack table-striped table-hover dataTableSearchOnly dateTableFilter" data-tablesaw-mode="stack">
											<thead>
												<tr>
													<th>Site</th>
													<th>Amount</th>
												</tr>
											</thead>
											<tbody>
												@if(sizeof($earnings))
													<!--Unpaid Earnings-->
													@foreach($earnings as $earning)
												<tr>
													<td class="text-center"><b class=" tablesaw-cell-label">Site</b>{{ $earning->site_name }}</td>
													<td class="text-center"><b class=" tablesaw-cell-label">Unpaid Earnings</b>$ {{ $earning->earnings }}</td>
												</tr>
													@endforeach
												@endif
											</tbody>
										</table>
									</div>
									@else
										<a href="/sites"><h3>Add Your Sites and Start Earning!</h3></a>
									@endif
								</div>
								</div>
							</div>
						</div>
						<br>
						<div class="row">
							<div class="col-lg-12">
								<div class="ibox">
								<div class="panel panel-default">
									<h4 class="p-title">Payment History</h4>
									@if($pub)
									<div class="ibox-content tableSearchOnly">
										<table class="tablesaw tablesaw-stack table-striped table-hover dataTableSearchOnly dateTableFilter" data-tablesaw-mode="stack">
											<thead>
												<tr>
													<th>Date</th>
													<th>Amount</th>
													<th>Status</th>
													<th>Method</th>
												</tr>
											</thead>
											<tbody>
												@if(sizeof($payments))
													@foreach($payments as $payment)
													<tr>
													<td class="text-center"><b class=" tablesaw-cell-label">Date</b>{{ $payment->transaction_date }}</td>
													<td class="text-center"><b class=" tablesaw-cell-label">Amount</b>$ {{ $payment->amount }}</td>
													<td class="text-center"><b class=" tablesaw-cell-label">Status</b>Status</td>
													<td class="text-center"><b class=" tablesaw-cell-label">Method</b>Method</td>
													</tr>
													@endforeach
												@endif

											</tbody>
										</table>
									</div>
									@endif
								</div>
								</div>
							</div>
                                                </div>
						</div>
					</div>
				</div>
				<div class="tab-pane table-responsive" id="advertiser-tab">
					<div class="ibox">
						<div class="ibox-content">
							<br>
							<h2 class="text-success"><strong>Advertiser Account Information</strong></h2>
							<div class="row">
								<div class="col-md-12">
									<div class="panel no-border">
										<div class="panel-body col-xs-12 col-md-6">
											<table class="table">
												<tbody>
													<tr>
														<td><strong>Account Status</strong></td>
	                                                                                                        @if($user->status)
													        <td>Confirmed</td>
                                                                                                                @else
                                                                                                                <td>Pending</td>
                                                                                                                @endif
													
													</tr>
													<tr>
														<td><strong>Current Balance</strong></td>
														<td>$ {{$balance}}</td>
													</tr>
													<tr>
														<td><strong>Spend for {{ date('F') }}</strong></td>
														<td>$&nbsp;{{$mtd}}</td>
													</tr>
												</tbody>
											</table>
										</div>
										<div class="col-xs-12 col-md-6 text-center">
											<a href="/addfunds"><button class="btn btn-md btn-primary">Fund Your Account!</button></a>
										</div>
									</div>
								</div>
							</div>
							<br>
							<div class="row">
								<div class="col-md-12">
									<div class="panel panel-default">
									<h4 class="p-title">Deposit History</h4>
										<div class="ibox-content">
											<div class="dataTableSearch">
												<table class="tablesaw tablesaw-stack table-striped table-hover dateTableFilter" data-tablesaw-mode="stack" id="dataTableSearch">
													<thead> 
														<tr>
															<th>Date</th>
															<th>Amount</th>
														</tr>
													</thead>
													<tbody>
                                                                                                                @foreach($invoices as $invoice)
														<tr>
															<td class="text-center"><b class=" tablesaw-cell-label">Date</b> {{$invoice->transaction_date}}</td>
															<td class="text-center"><b class=" tablesaw-cell-label">Amount</b> $&nbsp;{{$invoice->Amount}}</td>
														</tr>
                                                                                                                @endforeach
													</tbody>
												</table>
											</div>
										</div>
									</div>
								</div>
							</div>   
						</div>
					</div>
				</div>
			</div>  
		</div>     
    </div>
</div>
<script type="text/javascript">
$(document).ready(function(){
	$('#account_tab').click();
	
	$(".checkAll").click(function(event) {   
		if(this.checked) {
			// Iterate each checkbox
			$(':checkbox').each(function() {
				this.checked = true;                        
			});
		} else {
			$(':checkbox').each(function() {
				this.checked = false;                        
			});
		}
	});
});
	
$('.dataTableSearchOnly').DataTable({
	"oLanguage": {
	  "sSearch": "Search Table"
	}, pageLength: 10,
	responsive: true
});
	
$('#dataTableSearch').DataTable({
	pageLength: 10,
	responsive: true,
	dom: '<"html5buttons"B>lTfgitp',
	"columnDefs": [
		{ "orderable": false, "targets": 0 }
	],
	buttons: [
		{ extend: 'copy', },
		{extend: 'csv'},
		{extend: 'excel', title: 'ExampleFile'},
		{extend: 'pdf', title: 'ExampleFile'},

		{extend: 'print',
		 customize: function (win){
			$(win.document.body).addClass('white-bg');
			$(win.document.body).css('font-size', '10px');

			$(win.document.body).find('table')
					.addClass('compact')
					.css('font-size', 'inherit');
		}
		}
	]
});

</script>
   <script type="text/javascript">
       jQuery(document).ready(function ($) {
	       $('.nav-click').removeClass("active");
	       $('#nav_profile').addClass("active");
	       @if(session('status'))
		       @if(session('status_type'))
		       toastr.{{session('status_type')}}('{{session('status')}}');
	               @else
		       toastr.info('{{session('status')}}');
		       @endif
	       @endif

       });
   </script>
@endsection
