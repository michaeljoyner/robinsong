<?php

namespace App\Http\Controllers\Admin;

use App\Http\Requests\Admin\RegisterFormRequest;
use App\Http\Requests\EditUserFormRequest;
use App\User;
use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Input;

class UsersController extends Controller
{
    public function showRegister()
    {
        return view('admin.users.register');
    }

    public function postRegistration(RegisterFormRequest $request)
    {
        User::create($request->all());

        return redirect()->to('/');
    }

    public function edit($id)
    {
        $user = User::findOrFail($id);

        return view('admin.users.edit')->with(compact('user'));
    }

    public function update($id, EditUserFormRequest $request)
    {
        $user = User::findOrFail($id);
        $user->update($request->all());

        return redirect()->to('/');
    }

    public function delete($id)
    {
        if(User::all()->count() < 2) {
            return redirect()->back()->withErrors(['delete' => 'Can not delete final user']);
        }
        
        $user = User::findOrFail($id);

        $user->delete();

        return redirect()->to('/admin');
    }
}
