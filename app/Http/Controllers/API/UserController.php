<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Rules\HasLowercase;
use App\Rules\HasNumber;
use App\Rules\HasUppercase;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Auth;
use Hash;

class UserController extends Controller
{
    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $request->validate([
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => ['required', 'string', 'min:8', 'confirmed', new HasUppercase, new HasLowercase, new HasNumber],
        ]);

        $user = User::create([
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'api_token' => Str::random(60),
        ]);

        return response([
            'email' => $user->email,
            'token' => $user->api_token,
        ]);
    }

    public function login(Request $request) {
        $credentials = $request->only('email', 'password');

        if (Auth::attempt($credentials)) {
            $token = Auth::user()->api_token;
            $email = Auth::user()->email;

            return response([
                'email' => $email,
                'token' => $token,
            ]);
        }

        return response()->json(['error' => 'Login credentials do not match our records.'], 401);
    }
}
