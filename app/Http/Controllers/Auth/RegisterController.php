<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Teacher;
use App\Models\ActivityLog;
use Illuminate\Foundation\Auth\RegistersUsers;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class RegisterController extends Controller
{
    use RegistersUsers;

    protected $redirectTo = '/home';

    public function __construct()
    {
        $this->middleware('guest');
    }

    protected function validator(array $data)
    {
        return Validator::make($data, [
            'name'     => ['required', 'string', 'max:255'],
            'email'    => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
            'role'     => ['required', 'in:admin,teacher,student'],
        ]);
    }

    protected function create(array $data)
    {
        $user = User::create([
            'name'     => $data['name'],
            'email'    => $data['email'],
            'password' => Hash::make($data['password']),
            'role'     => $data['role'],
        ]);

        if ($data['role'] === 'teacher') {
            Teacher::create([
                'user_id' => $user->id,
                'name'    => $data['name'],
                'contact' => null,
            ]);
        }

        ActivityLog::create([
            'log_name'     => 'Account Created',
            'description'  => $data['role'] === 'teacher'
                                ? 'Teacher registered a new account: ' . $data['name']
                                : 'Student registered a new account: ' . $data['name'],
            'subject_type' => User::class,
            'subject_id'   => $user->id,
            'causer_type'  => User::class,
            'causer_id'    => $user->id,
            'properties'   => json_encode([
                'name'  => $data['name'],
                'email' => $data['email'],
                'role'  => $data['role'],
            ]),
        ]);

        return $user;
    }
}