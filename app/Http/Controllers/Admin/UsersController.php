<?php

namespace App\Http\Controllers\Admin;

use App\Http\Requests\Admin\RegisterFormRequest;
use App\Http\Requests\EditUserFormRequest;
use App\User;
use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;
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

    public function showPasswordReset()
    {
        return view('admin.users.resetpassword');
    }

    public function resetPassword(Request $request)
    {
        if(! Hash::check($request->current_password, $request->user()->password)) {
            return redirect()->back()->withErrors(['currentPassword' => 'Your current password is not correct.']);
        }

        $user = User::findOrFail($request->user()->id);
        $user->password = $request->password;
        $user->save();

        return redirect('admin');
    }
}
