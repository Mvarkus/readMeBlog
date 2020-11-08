<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;

class UserController extends Controller
{
    public function index()
    {
        return view('user.home', [
            'user' => Auth::user()
        ]);
    }

    public function editName()
    {
        return view('user.edit', [
            'user' => Auth::user()
        ]);
    }

    public function editEmail()
    {
        return view('user.edit-email', [
            'user' => Auth::user()
        ]);
    }

    public function update(Request $request)
    {
        $validator = $this->validator(
            $request->all(),
            [
                'firstName' => ['required', 'string', 'max:255'],
                'secondName' => ['required', 'string', 'max:255']
            ]
        );

        if ($validator->fails()) {
            return redirect('/user/edit')
                ->withErrors($validator)
                ->withInput();
        }

        $validData = $validator->valid();

        $user = Auth::user();
        $user->first_name  = $validData['firstName'];        
        $user->second_name = $validData['secondName'];             
        $user->save();

        return redirect('/user');
    }

    public function updateEmail(Request $request)
    {
        $validator = $this->validator(
            $request->all(),
            [
                'email' => ['required', 'string', 'email', 'max:255', 'unique:users']
            ]
        );

        if ($validator->fails()) {
            return redirect('/user/edit-email')
                ->withErrors($validator)
                ->withInput();
        }

        $validData = $validator->valid();

        $user = Auth::user();
        $user->email = $validData['email'];       
        $user->save();

        return redirect('/user');
    }


    public function destroy()
    {

        $user = Auth::user();
        Auth::logout();
        $user->deleteAvatarFile();
        $user->delete();
        
        return redirect('/');
    }

    /**
     * Get a validator for an incoming registration request.
     *
     * @param  array  $data
     * @return \Illuminate\Contracts\Validation\Validator
     */
    protected function validator(array $data, array $rules)
    {
        return Validator::make($data, $rules);
    }
}
