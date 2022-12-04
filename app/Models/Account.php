<?php

namespace App\Models;

use App\Models\Traits\ModelTrait;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Account extends Model
{
    use HasFactory;
    use ModelTrait;
    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'balance',
    ];

    protected $appends = [
        'name',
        'email'
    ];

    protected $hidden = [
        'accountUser',
        'created_at',
        'updated_at'
    ];

    public static function boot()
    {
        parent::boot();

        self::updating(function ($model) {
            if ($model->balance < 0) {
                throw new \Exception('Insufficient balance to cover this transfer', 422);
            }
        });

        self::created(function ($model) {
            $model->refresh();
        });
    }

    /**
     * attribute name accessor
     *
     * @return Attribute
     */
    protected function name(): Attribute
    {
        return new Attribute(
            get: fn () => $this->accountUser->name,
        );
    }

    /**
     * attribute email accessor
     *
     * @return Attribute
     */
    protected function email(): Attribute
    {
        return new Attribute(
            get: fn () => $this->accountUser->email,
        );
    }


    public function history()
    {
        return $this->transfers()->map(function ($row) {
            if ($row->from_account_id == $this->id) {
                return [
                    'trans_type' => 'Send',
                    'to_account_id' => $row->to_account_id,
                    'to_account_name' => Account::find($row->to_account_id)->name,
                    'amount' =>$row->amount,
                    'date' => $row->created_at,
                ];
            } else {
                return [
                    'trans_type' => 'Receive',
                    'from_account_id' => $row->from_account_id,
                    'from_account_name' => Account::find($row->from_account_id)->name,
                    'amount' =>$row->amount,
                    'date' => $row->created_at,
                ];
            }
        });
    }

    /**
     * Relationship to transer model
     */
    public function transfers()
    {
        return $this->sendTransfers->merge($this->receiveTransfers);
    }


    /**
     * Relationship to transer model
     */
    public function sendTransfers()
    {
        return $this->hasMany(Transfer::class, 'from_account_id', 'id');
    }


    /**
     * Relationship to transer model
     */
    public function receiveTransfers()
    {
        return $this->hasMany(Transfer::class, 'to_account_id', 'id');
    }

    public function accountUser()
    {
        return $this->belongsTo(AccountUser::class, 'account_user_id', 'id');
    }
}
