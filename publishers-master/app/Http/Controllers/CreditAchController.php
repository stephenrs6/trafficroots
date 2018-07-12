<?php

namespace App\Http\Controllers;
use App\Http\Requests;
use Illuminate\Http\Request;
use Validator;
use URL;
use Session;
use Redirect;
use Input;
use App\Bank;
use App\Transaction;
use App\Http\Controllers\CUtil;
use Auth;
use DB;
use Log;

class CreditAchController extends Controller
{
    /* handle funds via Velocity merchant account */

    public $amount = 0.00;
    
    public function __construct()
    {
        $this->middleware('auth');
    }   
    
    public function getIndex(Request $request)
    {
        /* show the page to add funds via CC or EFT */
        $amount = isset($request->deposit) ? intval($request->deposit) : 0.00;
        $balance = $this->getBalance();
        $user = Auth::getUser();
        $user_invoice = $user->id . "_" . uniqid();
        $path = $request->path();
        return view('addfunds_js',['balance' => $balance, 'user_invoice' => $user_invoice, 'user' => $user, 'amount' => $amount, 'request' => $request]);
    }
  
    public function postFunds(Request $request)
    {
        /* handle deposits after response from processor */
        

        return view('addfunds',['result' => $result]);   
    }

    public function getBalance()
    {
        $user = Auth::getUser();
        $bank = Bank::where('user_id', $user->id)->orderBy('id', 'desc')->first();
        if(!$bank){
            $data = array();
            $data['user_id'] = $user->id;
            $data['transaction_amount'] = 0.00;
            $data['running_balance'] = 0.00;
            $bank = new Bank();
            $bank->fill($data);
            $bank->save();
            $balance = 0;
        }else{
            $balance = $bank->running_balance;
        }
        return $balance;
    }

    public function depositFunds(Request $request)
    {
      try{
          if(($request->Status == 'Successful') && ($request->CaptureState == 'Captured')){

              $user = Auth::getUser();
	      $balance = $this->getBalance();
              $running_balance = ($balance + $request->Amount);
              $data = array('user_id' => $user->id, 'transaction_amount' => $request->Amount, 'running_balance' => $running_balance);
              $bank = new Bank();
              $bank->fill($data);
              $bank->save();
               
              $insert = $request->all();
              $insert['user_id'] = $user->id;
              $insert['bank_id'] = $bank->id;
              $insert['transaction_date'] = date('Y-m-d');
              $insert['created_at'] = date('Y-m-d H:i:s');
              $transaction = new Transaction();
              $transaction->fill($insert);
	      $transaction->save();
	      Log::info($user->name." deposited $ ".$request->amount." to make their balance $ ".$running_balance);
	      $cutil = new CUtil();
	      $cutil->logit($user->name." deposited $ ".$request->amount." to make their balance $ ".$running_balance);
	      //$cutil->sendText($user->name." deposited $ ".$request->amount." to make their balance $ ".$running_balance);
              return response('OK!',200);
           }else{
              return response('Failed! '.$request->CaptureState,200);
           }
         
      }catch(Exception $e){
          Log::error($e->getMessage());
          return response($e->getMessage(),200);
      }

    }
}
