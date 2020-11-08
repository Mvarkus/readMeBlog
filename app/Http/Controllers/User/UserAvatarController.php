<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;

class UserAvatarController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function create(Request $request)
    {
        $validData = $request->validate([
            'avatar' => 'required|file|image'
        ]);

        $storage = env('APP_ENV') === 'testing' ? 'test' : 'public';

        $user = Auth::user();

        $user->avatar = $validData['avatar']->store('images/avatars', $storage);
        $user->save();
        
        return redirect('/user');
    }

    public function default()
    {
        $user = Auth::user();
        
        $user->deleteAvatarFile();

        $user->avatar = 'images/avatars/default.png';
        $user->save();

        return redirect('/user');
    }
}
