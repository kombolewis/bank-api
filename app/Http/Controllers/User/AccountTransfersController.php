<?php

namespace App\Http\Controllers\User;

use App\Models\Account;
use App\Models\Transfer;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class AccountTransfersController extends Controller
{
    public function show(Account $account)
    {
        return $account->history();
    }

    public function store(Request $request)
    {
        $request->validate([
            'from_account_id' => 'required|int',
            'to_account_id' => 'required|int',
            'amount' => 'required|int'
        ]);

        $message =  'transfer executed successfully';
        $status =  201;
        try {
            (new Transfer())->executeTransfer($request->all());
        } catch(\Exception $e) {
            $status =  $e->getCode();
            $message =  "transfer failed: " .  $e->getMessage();
        }
        return response()->json(['message' => $message], $status);
    }
}
