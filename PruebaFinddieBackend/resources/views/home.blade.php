<?php
    use App\Account;

    $user_accounts = Account::where('user_id',Auth::user()->id)->get();
    // $error_payment = isset($error_payment) ? $error_payment : '';
    // $payment_error = 'No Account';
?>

@extends('layouts.app')

@section('content')
<div class="container">

    @isset($success_payment)
        <div class="alert alert-success">{{ $success_payment }}</div>
    @endisset

    @isset($payment_error)
        <div class="alert alert-danger">{{ $payment_error }}</div>
    @endisset

    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">Accounts of this user</div>

                <div class="card-body">
                    @if (session('status'))
                        <div class="alert alert-success" role="alert">
                            {{ session('status') }}
                        </div>
                    @endif

                    <table class="table">
                        <thead class="thead-dark">
                            <th>Account</th>
                            <th>Type</th>
                            <th>Amount</th>
                            {{-- <th>Actions</th> --}}
                        </thead>
                        <tbody>
                            @foreach ($user_accounts as $account)
                                <tr>
                                    <td>{{ substr($account->id,0,4).' '.substr($account->id,4,4).' '.substr($account->id,8,4).' '.substr($account->id,12,4)}}</td>
                                    <td>{{ $account->type }}</td>
                                    <td>${{ number_format($account->amount,2) }}</td>
                                    {{-- <td>
                                        <button class="btn btn-danger">Delete</button>
                                        <a data-confirm="Are you sure?" data-method="delete" href="account/{{ $account->id }}" rel="nofollow">Delete</a>
                                    </td> --}}
                                </tr>
                            @endforeach
                        </tbody>
                    </table>

                </div>
            </div>
        </div>
    </div>
    <div class="row justify-content-center mt-5">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">Create Account</div>
                <div class="card-body">
                    <form action="account" method="post">
                        {{ csrf_field() }}
                        <div class="col-md 6">
                            <label for="accountType">Account type</label>
                            <select name="type" id="accountType" class="custom-select">
                                <option value="Credit">Credit</option>
                                <option value="Debit">Debit</option>
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label for="amount">Amount</label>
                            <input type="text" name="amount" placeholder="$0.00" id="amount" class="form-control">
                        </div>
                        <div class="row mt-2">
                            <div class="col-md-3 offset-md-9">
                                <button class="btn btn-block btn-primary btn-sm" type="submit">Add</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <div class="row justify-content-center mt-5">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">Make Withdraw</div>
                <div class="card-body">
                    <form method="POST" action="withdraw">
                        {{ csrf_field() }}
                        <div class="form-row">
                            <div class="col-md-6">
                                <label for="withdraw_account">Account to withdraw</label>
                                <select name="withdraw_account" id="withdraw_account" class="custom-select">
                                    @foreach ($user_accounts as $account)
                                        @if ($account->type == 'Credit')
                                            <option value="{{ $account->id }}"> {{ substr($account->id,0,4).' '.substr($account->id,4,4).' '.substr($account->id,8,4).' '.substr($account->id,12,4) }}</option>    
                                        @endif
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-6">
                                <label for="withdraw_amount">Amount to withdraw</label>
                                <input type="text" name="withdraw_amount" id="withdraw_amount" autocomplete="off" placeholder="$0.00" class="form-control">
                            </div>
                        </div>
                        <div class="offset-md-9 col-md-3">
                            <button class="btn mt-1 btn-primary btn-block" type="submit">Withdraw</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <div class="row justify-content-center mt-5">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">Make Deposit</div>
                <div class="card-body">
                    <form method="POST" action="deposit">
                        {{ csrf_field() }}
                        <div class="form-row">
                            <div class="col-md-6">
                                <label for="deposit_account">Account to deposit</label>
                                <select name="deposit_account" id="deposit_account" class="custom-select">
                                    @foreach ($user_accounts as $account)
                                        <option value="{{ $account->id }}"> {{ substr($account->id,0,4).' '.substr($account->id,4,4).' '.substr($account->id,8,4).' '.substr($account->id,12,4) }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-6">
                                <label for="deposit_amount">Amount to deposit</label>
                                <input type="text" name="deposit_amount" id="deposit_amount" autocomplete="off" placeholder="$0.00" class="form-control">
                            </div>
                        </div>
                        <div class="offset-md-9 col-md-3">
                            <button class="btn mt-1 btn-primary btn-block" type="submit">Deposit</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <div class="row justify-content-center mt-5">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">Make Payments</div>
                <div class="card-body">
                    <form method="POST" action="pay">
                        {{ csrf_field() }}
                        <div class="form-row">
                            <div class="col-md-6">
                                <label for="pay_account">Account with pay method</label>
                                <select name="pay_account" id="pay_account" class="custom-select">
                                    @foreach ($user_accounts as $account)
                                        @if ($account->type == 'Credit')
                                            <option value="{{ $account->id }}"> {{ substr($account->id,0,4).' '.substr($account->id,4,4).' '.substr($account->id,8,4).' '.substr($account->id,12,4) }}</option>
                                        @endif
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-6">
                                <label for="pay_amount">Amount with pay method</label>
                                <input type="text" name="pay_amount" id="pay_amount" autocomplete="off" placeholder="$0.00" class="form-control">
                            </div>
                        </div>
                        <div class="offset-md-9 col-md-3">
                            <button class="btn mt-1 btn-primary btn-block" type="submit">Pay</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection