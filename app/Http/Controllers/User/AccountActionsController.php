<?php

namespace App\Http\Controllers\User;

use App\Models\Account;
use App\Models\Transfer;
use App\Models\AccountUser;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class AccountActionsController extends Controller
{
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255',
            'deposit' => 'required|string',
        ]);

        return AccountUser::firstOrCreate(
            ['email' => $request->email],
            ['name' => $request->name]
        )->accounts()->create(
            [
                'balance' => $request->deposit
            ]
        );
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Account  $account
     * @return \Illuminate\Http\Response
     */
    public function show(Account $account)
    {
        return $account;
        // return response()->json([
        //     'balance' => $account->balance
        // ], 200);
    }
}
