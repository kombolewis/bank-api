<?php

namespace App\Models;

use App\Models\Traits\ModelTrait;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Transfer extends Model
{
    use HasFactory;
    use ModelTrait;

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
     * validate model before/after save
     *
     * @return void
     */
    public static function boot()
    {
        parent::boot();

        self::creating(function ($model) {
            if ($model->from_account_id == $model->to_account_id) {
                throw new \Exception('Cannot move funds between same account', 400);
            }
        });
    }
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
}
