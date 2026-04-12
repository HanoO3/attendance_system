<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\Request;

class LoginController extends Controller
{
    use AuthenticatesUsers;

    public function __construct()
    {
        $this->middleware('guest')->except('logout');
    }

protected function authenticated(Request $request, $user)
{
    $role = strtolower($user->role);

    if ($role === 'student') {
        return redirect()->route('student.dashboard');
    }

    if ($role === 'teacher') {
        return redirect()->route('teacher.dashboard'); 
    }

    return redirect()->route('dashboard');
}

    protected function credentials(Request $request)
    {
        $login = $request->input('email'); 

        if (!filter_var($login, FILTER_VALIDATE_EMAIL)) {
            return [
                'email' => $login . '@gmail.com', 
                'password' => $request->input('password')
            ];
        }

        return $request->only($this->username(), 'password');
    }
}