<?php

namespace App\Models;

use App\Models\Traits\ModelTrait;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class AccountUser extends Model
{
    use HasFactory;
    use ModelTrait;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
    ];
    /**
     * child table accounts relationship
     *
     * @return void
     */
    public function accounts()
    {
        return $this->hasMany(Account::class, 'account_user_id', 'id');
    }
}
