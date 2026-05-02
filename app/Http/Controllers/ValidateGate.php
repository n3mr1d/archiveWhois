<?php

namespace App\Http\Controllers;

use App\Http\Requests\LoginRequest;
use App\Http\Requests\RegisterRequest;
use App\Models\User;
use Auth;
use Illuminate\Http\Request;

class ValidateGate extends Controller
{
    public function index()
    {
        return view('login');
    }
    public function validateLogin(LoginRequest $login)
    {
        $validate = $login->validated();
        if (Auth::attempt($validate)) {
            return redirect()->route('dashboard');
        }
        return redirect()->route('login')->withErrors(['error' => 'Username or password is not our record.']);
    }


    public function registerIndex()
    {
        return view('register');
    }
    public function validateRegister(RegisterRequest $request)
    {
        // store username password dan email 
        $validate = $request->validated();
        $store = User::create([
            'username' => $validate['username'],
            'email' => $validate['email'],
            'password' => $validate['password'],
        ]);

        if ($store) {
            return redirect()->route('login')->with('success', 'Account created successfully!');
        }
        return redirect()->route('register')->with('error', 'Account creation failed!');
    }
}
