<?php

namespace App\Models;

use Exception;
use Awobaz\Compoships\Compoships;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Transfer extends Model
{
    use HasFactory;
    use Compoships;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'from_account_id',
        'to_account_id',
        'amount',
    ];

    /**
     * Execute transfer action
     *
     * @param array $transfer
     * @return void
     */
    public function executeTransfer(array $transfer): bool
    {
        DB::transaction(function () use ($transfer) {
            $this->create($transfer);
            $fromAccount = Account::find($transfer['from_account_id']);
            $fromAccount->balance = $fromAccount->balance - $transfer['amount'];
            $fromAccount->save();

            $toAccount = Account::find($transfer['to_account_id']);
            $toAccount->balance = $toAccount->balance + $transfer['amount'];
            $toAccount->save();
        });

        if ($this->id) {
            return true;
        }
        return false;
    }

    public function account()
    {
        return $this->belongsTo(Account::class, ['id','id'], ['from_account_id','to_account_id']);
    }
}
