<?php

namespace App\Http\Controllers\Admin;

use App\Models\Role;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Http\Controllers\Controller;

class AdminController extends Controller
{
    /**
     * view registered users
     *
     * @return Illuminate\Http\Response
     */
    public function index()
    {
        return response()->json(User::all());
    }
    /**
     * edit/assign roles for users
     *
     * @param Request $request
     * @param User $user
     * @return Response
     */
    public function store(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'roles' => 'required|array',
        ]);

        $user = User::where('email', $request->email)->firstOrFail();

        $roles = collect($request->roles)->map(function ($role) {
            return Role::where('name', $role)->get()->pluck('id')->first();
        });

        $user->roles()->sync($roles);

        return $user;
    }
}
