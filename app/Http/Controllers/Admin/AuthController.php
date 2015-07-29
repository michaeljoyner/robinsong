<?php

namespace App\Http\Controllers\Admin;

use App\Http\Requests\LoginFormRequest;
use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    public function showLogin()
    {
        return view('admin.login');
    }

    public function doLogin(LoginFormRequest $request)
    {
        $credentials = $request->only('email', 'password');
        $toRemember = $request->has('remember_me');

        if(Auth::attempt($credentials, $toRemember)) {
            return redirect()->intended('/admin');
        }

        return redirect()->back();
    }

    public function doLogout()
    {
        Auth::logout();

        return redirect()->to('/');
    }
}
