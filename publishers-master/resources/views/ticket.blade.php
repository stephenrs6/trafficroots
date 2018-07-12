@extends('layouts.app')
@section('title', 'Support Ticket Details')
@section('content')
<div class="content">
	<div class="row">	
		<div class="col-xs-12">
			<div class="panel panel-default">
				<a href="/tickets"><button type="button" class="btn btn-primary btn-xs pull-right m-t m-r" data-toggle="modal" data-target="#ticketModal" id="create-ticket"><i class="fa fa-cogs"></i>&nbsp;Back to Tickets</button></a>
				<h4 class="p-title">Ticket {{ $ticket->id }}</h4>
				<div class="ibox-content">
					<table class="tablesaw tablesaw-stack table-striped table-hover dataTableSearchOnly dateTableFilter" data-tablesaw-mode="stack">
						<thead>
							<tr>
								<th>Ticket ID</th>
								<th>Subject</th>
								<th>Type</th>
								<th>Status</th>
								<th>Date Created</th>
								<th>Date Updated</th>
							</tr>
						</thead>
						<tbody>
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
								<td class="text-center"><b class=" tablesaw-cell-label">Date Updated</b>{{$ticket->updated_at}}</td>
							</tr>
						</tbody>
					</table>
				</div>
				<div class="ibox-content customerResponse">
					<div class="row">
						<div class="col-md-2"><b><i>Me:</i></b><br />{{ $ticket->created_at }}</div>
						<div class="col-md-5">
							<div class="well">{{ stripslashes($ticket->description) }}</div>
						</div>
						<div class="col-md-5"></div>
					</div>
					@foreach(App\Ticket::find($ticket->id)->replies as $reply)
					<div class="row adminResponse 
							@if(!$reply->admin)
							 alert-info
							@endif
							">
						@if($reply->admin)
						<div class="col-md-5"></div>
						<div class="col-md-5">
							<div class="well">
								{{ stripslashes($reply->comments) }}
							</div>
						</div>
						<div class="col-md-2"><b><i>Admin:</i></b><br />{{ $reply->created_at }}</div>
					</div>
					@else 
					<div class="col-md-2"><b><i>Me:</i></b><br />{{ $reply->created_at }}</div>
					<div class="col-md-5">
						<div class="well">{{ stripslashes($reply->comments) }}</div>
					</div>
					<div class="col-md-5"></div>
				</div>
				@endif
                @endforeach

            	<div class="ibox-content">
					<h4>Add A Reply</h4>
					<form name="reply_form" id="reply_form" action="/reply" method="POST">
						<input type="hidden" name="ticket_id" id="ticket_id" value="{{ $ticket->id }}">
						<div class="control-group">
							<label class="control-label" for="description">Comments</label>
							<div class="controls">
								<textarea id="comments" name="comments" class="border-radius-none" rows="10" cols="60" required></textarea>
							</div>
						</div>
                    <div class="control-group">
                        {{ csrf_field() }}
                    <br /><br />
                    <div class="controls">
                        <input type="submit" value="Submit Reply">
                   </div>
                   </div>
                </form>
            	</div>
        	</div>
		</div>
	</div>
</div>
   <script type="text/javascript">
       jQuery(document).ready(function ($) {
	       $('.nav-click').removeClass("active");
	       $('#nav_support').addClass("active");
       });
   </script>
@endsection
