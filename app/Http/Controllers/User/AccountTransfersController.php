<?php

namespace App\Http\Controllers\User;

use App\Models\Account;
use App\Models\Transfer;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class AccountTransfersController extends Controller
{
    /**
     * Show account transfer history
     *
     * @param Account $account
     * @return \Illuminate\Http\Response
     *
     */
    public function show(Account $account)
    {
        return $account->history();
    }

    /**
     * create a new transfer
     *
     * @param Request $request
     * @return \Illuminate\Http\Response
     *
     */
    public function store(Request $request)
    {
        $request->validate([
            'from_account_id' => 'required|int',
            'to_account_id' => 'required|int',
            'amount' => 'required|numeric'
        ]);

        try {
            (new Transfer())->executeTransfer($request->all());
            return response()->json(['message' => 'transfer executed successfully'], 201);
        } catch(\Exception $e) {
            $status =  $e->getCode();
            if ($status  == 400 || $status == 422) {
                $message =  "transfer failed: " .  $e->getMessage();
            } else {
                $message =  "could not complete transfer";
                $status =  500;
            }
            return response()->json(['message' => $message], $status);
        }
    }
}
