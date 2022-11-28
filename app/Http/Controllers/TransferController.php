<?php

namespace App\Http\Controllers;

use App\Models\Transfer;
use Illuminate\Http\Request;

class TransferController extends Controller
{
    public function transfer(Request $request)
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
