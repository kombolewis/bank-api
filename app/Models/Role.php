<?php

namespace App\Models;

use App\Models\Traits\ModelTrait;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Role extends Model
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
        'description'
    ];

    /**
     * @inheritDoc
     *
     */
    protected $hidden = [
        'created_at',
        'updated_at'
    ];

    public function users()
    {
        return $this->belongsToMany(User::class);
    }
}
