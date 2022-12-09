<?php

namespace App\Http\Controllers\User;

use App\Models\Account;
use App\Models\Transfer;
use App\Models\AccountUser;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class AccountActionsController extends Controller
{
    /**
     * Create a new account
     *
     * @param Request $request
     * @return \Illuminate\Http\Response     *
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'currency' => 'string|max:255',
            'email' => 'required|string|email|max:255',
            'deposit' => 'required|numeric',
        ]);

        try {
            return AccountUser::firstOrCreate(
                ['email' => $request->email],
                ['name' => $request->name]
            )->accounts()->create(
                [
                    'balance' => $request->deposit
                ]
            );
        } catch (\Throwable $th) {
            return response()->json(['message' => 'could not create account'], 500);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Account  $account
     * @return \Illuminate\Http\Response
     */
    public function show(Account $account)
    {
        return $account->only(['name','currency','balance']);
    }
}
