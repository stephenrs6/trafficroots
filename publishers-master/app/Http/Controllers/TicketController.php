<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Ticket;
use App\TicketType;
use App\TicketReply;
use Auth;
use Log;

class TicketController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $user = Auth::getUser();
        $mytickets = Ticket::where('user_id', $user->id)->get();
        $ticket_types = TicketType::all();
        return view('tickets', ['mytickets' => $mytickets, 'ticket_types' => $ticket_types]);        
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }
    public function reply(Request $request)
    {
	    $user = Auth::getUser();
	    if($request->has('ticket_id')){
	        $ticket = Ticket::where('id', $request->get('ticket_id'))->where('user_id', $user->id)->get();
		if(sizeof($ticket)){
                    $comments = addslashes($request->comments);
		    $reply = new TicketReply();
		    $data = array('ticket_id' => $request->ticket_id, 'comments' => $comments, 'created_at' => date('Y-m-d H:i:s'));
		    $reply->fill($data);
		    $reply->save();
		    Ticket::where('id', $request->ticket_id)->update(array('status' => 0));
		    Log::info(print_r($data, true));
                    return redirect('/ticket/'.$request->ticket_id);

		}	
		return false;
	    }
	    return false;
    }
    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $user = Auth::getUser();
        $data = $request->all();
        $data['user_id'] = $user->id;
        $ticket = new Ticket();
        $ticket->fill($data);
        $ticket->save();
        return redirect('/tickets');
        
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(Request $request)
    {
        $ticket = Ticket::where('id', $request->id)->first();
	return view('ticket', array('ticket' => $ticket));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
