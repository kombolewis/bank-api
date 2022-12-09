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
        try {
            return response()->json(User::all());
        } catch (\Throwable $th) {
            return response()->json(['message' => 'could not retrieve users'], 500);
        }
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
            'roles' => 'present|array',
        ]);

        $user = User::where('email', $request->email)->first();

        if (!$user) {
            return response()->json(['message' => 'user not found'], 400);
        }

        try {
            return $user->assignRoles($request->roles);
        } catch (\Throwable $th) {
            return response()->json(['message' => 'could not assign role to user'], 400);
        }
    }
}
