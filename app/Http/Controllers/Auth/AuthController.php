<?php

namespace App\Http\Controllers\Auth;

use App\Models\Role;
use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class AuthController extends Controller
{
    /**
     * login a user with passport
     *
     * @param Request $request
     * @return \Illuminate\Http\Response     *
     */
    public function login(Request $request)
    {
        $request->validate([
            'username' => 'required|string|email',
            'password' => 'required|string',
        ]);
        $req = Request::create(config('services.passport.login_endpoint'), 'POST', [
            'grant_type' => 'password',
            'client_id' => config('services.passport.client_id'),
            'client_secret' => config('services.passport.client_secret'),
            'username' => $request->username,
            'password' => $request->password,

        ]);


        return app()->handle($req);
    }

    /**
     * Register new users
     *
     * @param Request $request
     * @return \Illuminate\Http\Response     *
     */
    public function register(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:6',
        ]);

        return User::create($request->all());
    }

    /**
     * Remove granted tokens/log out a user
     *
     * @return \Illuminate\Http\Response     *
     *
     */
    public function logout()
    {
        auth()->user()->tokens->each(function ($token) {
            $token->delete();
        });

        return response()->json('logged out successfully', 200);
    }
}
