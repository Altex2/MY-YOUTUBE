<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

class AuthController extends Controller
{
    public function create(){
        return view("auth.register");
    }

    public function store(Request $request){
        $credentials = $request->validate([
            'first_name' => 'required|string|min:2',
            'last_name' => 'required|string|min:2',
            'email' => 'required|email|unique:users',
            'password' => 'required|string|min:8|confirmed',
            'password_confirmation' => "required|string|min:8"
        ]);

        $user = \App\Models\User::create([
            'first_name' => $credentials['first_name'],
            'last_name' => $credentials['last_name'],
            'email' => $credentials['email'],
            'password' => Hash::make($credentials['password']),
        ]);

        return view('auth.login');
    }

    public function login()
    {
        return view('auth.login');
    }

    public function auth(Request $request){
        $credentials = $request->validate([
            'email' => 'required|email|exists:users',
            'password' => 'required|string',
        ]);

        if(!Auth::attempt($credentials)){
            throw ValidationException::withMessages([
                'email' => 'Invalid Credentials!'
            ]);
        }

        request()->session()->regenerate();

        return redirect('/');
    }

    public function destroy(){
        Auth::logout();
        return redirect('/');
    }
}
