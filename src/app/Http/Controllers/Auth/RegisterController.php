<?php

namespace App\Http\Controllers\Auth;

use App\Http\Requests\RegisterRequest;
use Laravel\Fortify\Contracts\CreatesNewUsers;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;

class RegisterController extends Controller
{
    public function showRegistrationForm(){
        return view('auth.register');
    }

    public function register(RegisterRequest $request){
        $data = $request->validated();

        $user = app(CreatesNewUsers::class)->create([
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => $data['password']
        ]);

        Auth::login($user);

        return redirect('/mypage/profile');
    }
}
