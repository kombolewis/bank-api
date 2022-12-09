<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;

use App\Models\Traits\ModelTrait;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Support\Facades\Hash;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Support\Arr;

class User extends Authenticatable
{
    use HasApiTokens;
    use HasFactory;
    use Notifiable;
    use ModelTrait;


    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'roles',
        'password',
        'remember_token',
        'email_verified_at',
        'created_at',
        'updated_at',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    protected $appends = [
        'role'
    ];

    /**
     * Roles table many to many relationship
     *
     * @return Builder
     */
    public function roles()
    {
        return $this->belongsToMany(Role::class);
    }
    /**
     * attribute name accessor
     *
     * @return Attribute
     */
    protected function role(): Attribute
    {
        return new Attribute(
            get: fn () => $this->roles->pluck('name'),
        );
    }

    /**
     * mutator for password fiels
     *
     * @param [type] $password
     * @return void
     */
    public function setPasswordAttribute($password)
    {
        $this->attributes['password'] = Hash::make($password);
    }

    /**
     * check if user has specified role
     *
     * @param string $role
     * @return boolean
     */
    public function hasRole(string $role): bool
    {
        return null !== $this->roles()->where('name', $role)->first();
    }

    /**
     * check if user has specified roles
     *
     * @param array $roles
     * @return boolean
     */
    public function hasRoles(array $roles): bool
    {
        return null !== $this->roles()->whereIn('name', $roles)->first();
    }

    /**
     * assign roles to user
     *
     * @param array $roles
     * @return self
     */
    public function assignRoles(array $roles): self
    {
        $roles = collect($roles)->map(function ($role) {
            return Role::where('name', $role)->get()->pluck('id')->first();
        });
        $this->roles()->sync($roles);
        return $this;
    }
}
