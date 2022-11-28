<?php

namespace App\Models;

use Awobaz\Compoships\Compoships;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Account extends Model
{
    use HasFactory;
    use Compoships;
    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'user_id',
        'balance',
    ];

    public static function boot()
    {
        parent::boot();

        self::updating(function ($model) {
            if ($model->balance < 0) {
                throw new \Exception('Insufficient balance to cover this transfer', 422);
            }
        });
    }

    public function history()
    {
        return $this->transfers;
    }

    /**
     * Relationship to transer model
     */
    public function transfers()
    {
        return $this->hasMany(Transfer::class, ['from_account_id','to_account_id'], ['id','id']);
    }
}
