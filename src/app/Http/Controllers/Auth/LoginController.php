<?php

namespace App\Http\Controllers\Auth;

use App\Http\Requests\LoginRequest;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class LoginController extends Controller
{
    public function showLoginForm(){
        return view('auth.login');
    }

    public function login(LoginRequest $request){
        $data = $request->validated();

        // Fortifyのログイン機能を利用
        if (Auth::attempt([
            'email' => $data['email'],
            'password' => $data['password'],
        ])) {
            // ログイン成功時
            return redirect('/?tab=mylist');
        }

        // ログイン失敗時
        return back()->withErrors([
            'password' => 'ログイン情報が登録されていません',
        ])->withInput();
    }
}