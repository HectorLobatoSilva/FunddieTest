<?php

namespace App\Http\Controllers;

use App\Account;
use Illuminate\Http\Request;
use Auth;

class AccountController extends Controller
{

    public function __construct()
    {
        $this->middleware('auth');
    }

    public function store(Request $request)
    {
        $account_id = mt_rand(1000000000000000,9999999999999999);
        $array = array_merge($request->all(),['id'=>$account_id,'user_id'=>Auth::user()->id]);
        Account::create($array);
        return redirect('/home');
    }

    public function show($id)
    {
        return Account::find($id)->first();
    }

    public function update(Request $request, $id)
    {
        Account::find($id)->update($request->all());
    }
    public function destroy($id)
    {
        // return $id;
        Account::find($id)->delete();
        return redirect('/home');
    }

    public function Deposit(Request $request)
    {
        // return $request->all();
        $account = Account::find($request->deposit_account);
        $account->update(['amount'=> $account->amount + $request->deposit_amount]);
        return redirect()->route('home');
    }

    public function Withdraw(Request $request)
    {
        $account = Account::find($request->withdraw_account);
        if ($account->type == 'Credit') {
            if ($request->amount_to_withdraw <= $account->amount) {
                $real_withdraw = $request->withdraw_amount / 1.10;
                $account->update(['amount'=>$account->amount - $real_withdraw]);
                return redirect()->route('home');
                return 'Withdrawal made with 10% free successfully';
            } else {
                return 'insufficient credit';
            }
        } elseif ($account->type == 'Debit') {
            if ($account->amount > 0 AND $account->amount >= $request->amount_to_withdraw) {
                $account->update(['amoun'=>$account->amount - $request->amount_to_withdraw]);
                return 'Withdraw make successfully';
            }
        }
        return 'Conditions do not work';
    }

    public function Pay(Request $request)
    {
        // return $request->all();
        $account = Account::where('id',$request->pay_account)->first();
        // return $account;
        if ($account->type == 'Credit') {
            if ($request->pay_amount <= $account->amount) {
                // Account::where('id',$account->id)
                $withdraw = $account->amount - $request->pay_amount;
                $account->update(['amount'=>$withdraw]);
                // return 'Payment made successfully';
                return redirect()->route('home',['payment_success'=>'Payment made successfully']);
            } else {
                // return 'insufficient credit';
                return redirect()->route('home',['payment_error'=>'nsufficient credit']);
            }
        } else {
            // return "This account can not make payments";
            return redirect()->route('home',['payment_success'=>'This account can not make payments']);
        }
    }
}
