<?php

namespace App\Http\Controllers;

use App\Models\ProgramCodeListDB\KaryalayaCodeList;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    public function login(Request $request)
    {
        $login = $request->validate([
            'email' => 'required|string',
            'password' => 'required|string'
        ]);

        if (!Auth::attempt($login)) {
            return response([
                'status' => 402,
                'type'=>'error',
                'message' => 'Invalid login credentials.'
            ]);
        }

        $accessToken = Auth::user()->createToken('authToken')->accessToken;

        return response([
            'status' => 200,
            'type'=> 'success',
            'message' => 'User login successful.',
            'data' => [
                'user' => Auth::user(),
                'access_token' => $accessToken
            ]
        ]);
    }

    public function register(Request $request)
    {
        $register = $request->validate([
            'name' => 'required|string',
            'email' => 'email|required|string|unique:users',
            'password' => 'required|string|confirmed|min:8',
        ]);

        $register['password'] = Hash::make($register['password']);

        $login = ([
            'email' => $request->email,
            'password' => $request->password,
        ]);

        if (User::create($register)) {
            if (Auth::attempt($login)) {
                $accessToken = Auth::user()->createToken('authToken')->accessToken;
                return response([
                    'status' => 200,
                    'type'=> 'success',
                    'message' => 'User registration and login successful.',
                    'data' => [
                        'user' => Auth::user(),
                        'access_token' => $accessToken
                    ]
                ]);
            }
        }

        return response([
            'status' => 402,
            'type'=> 'error',
            'message' => 'User registration not successful.'
        ]);
    }

    public function logout(Request $request)
    {
        $accessToken = Auth::user()->token();
        if ($accessToken->revoke()) {
            return response([
                'status' => 200,
                'type'=> 'success',
                'message' => 'User logout successful.'
            ]);
        };
        return response([
            'status' => 402,
            'type'=> 'error',
            'message' => 'User logout not successful.'
        ]);
    }
}
