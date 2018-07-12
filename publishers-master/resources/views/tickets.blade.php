@extends('layouts.app')
@section('title', 'Support')
@section('content')
<div class="content">
	<div class="row">	
		<div class="col-xs-12">
			<div class="panel panel-default">
				<button type="button" class="btn btn-primary btn-xs pull-right m-t m-r" data-toggle="modal" data-target="#ticketModal" id="create-ticket"><span class="fa fa-plus-square-o"></span>&nbsp;&nbsp; Create Ticket</button>
				<h4 class="p-title">My New Ticket</h4>
				
				<div class="ibox-content">
					<div class="tableSearchOnly">
						<table class="tablesaw tablesaw-stack table-striped table-hover dataTableSearchOnly dateTableFilter" data-tablesaw-mode="stack">
							<thead>
								<tr>
									<th>Ticket ID</th>
									<th>Subject</th>
									<th>Type</th>
									<th>Status</th>
									<th>Date Created</th>
									<th>Date Update</th>
									<th>Details</th>
								</tr>
							</thead>
							<tbody>
								@foreach($mytickets as $ticket)
								<tr>
									<td class="text-center"><b class=" tablesaw-cell-label">Ticket ID</b>{{$ticket->id}}</td>
									<td class="text-center"><b class=" tablesaw-cell-label">Subject</b>{{$ticket->subject}}</td>
									<td class="text-center"><b class=" tablesaw-cell-label">Type</b>{{$ticket->type}}</td>
									<td class="text-center"><b class=" tablesaw-cell-label">Status</b>
                                                                        @if($ticket->status == 0)
                                                                        Pending
                                                                        @elseif($ticket->status == 1)
                                                                        Replied
                                                                        @elseif($ticket->status == 2)
                                                                        Closed
                                                                        @endif
                                                                        </td>
									<td class="text-center"><b class=" tablesaw-cell-label">Date Created</b>{{$ticket->created_at}}</td>
									<td class="text-center"><b class=" tablesaw-cell-label">Date Update</b>{{$ticket->updated_at}}</td>
									<td class="text-center" data-ticket-id="{{ $ticket->id }}"><b class=" tablesaw-cell-label">Preview</b>
										<a href="/ticket/{{ $ticket->id }}"
										   class="site-edit fa fa-cogs">
										</a>
									</td>
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
	
<div class="modal fade" id="ticketModal" role="dialog">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header">
			  <button type="button" class="close" data-dismiss="modal">&times;</button>
			  <h4 class="modal-title">Create Support Ticket</h4>
			</div>
			<form name="ticket_form" id="ticket_form" action="" method="POST">
				<div class="modal-body">
					<div class="form-group">
						<label class="form-label" for="location_type">Ticket Type</label>
						<select id="type" name="type" class="form-control" required>
							<option value="">Choose One</option>
							@foreach($ticket_types as $ticket_type)
							<option value="{{$ticket_type->id}}">{{$ticket_type->description}}</option>
							@endforeach
						</select>
					</div>
					<div class="form-group">
						<label class="control-label" for="subject">Ticket Subject</label>
						<input type="text" size="40" id="subject" name="subject" class="form-control" maxlength="50" required>
					</div>
					<div class="form-group">
						<label class="control-label" for="description">Ticket Description</label>
						<textarea id="description" name="description" class="form-control" rows="10" cols="60" required></textarea>
					</div>
					<div class="form-group">
						{{ csrf_field() }}
					</div>
				</div>
				<div class="modal-footer">
					<button type="submit" class="btn btn-primary">Submit</button>
					<button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
				</div>
			</form>
		</div>
	</div>
</div>

   <script type="text/javascript">
	   $('.dataTableSearchOnly').DataTable({
	"oLanguage": {
	  "sSearch": "Search Table"
	}, pageLength: 25,
	responsive: true
});	
	   
       jQuery(document).ready(function ($) {
	       $('.nav-click').removeClass("active");
	       $('#nav_support').addClass("active");
       });
   </script>
@endsection
