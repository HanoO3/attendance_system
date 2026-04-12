<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class HomeController extends Controller
{
    public function index()
    {
        $role = auth()->user()->role;

        if ($role == 'student') {
            return redirect()->route('student.dashboard');
        } elseif ($role == 'teacher') {
            return redirect()->route('teacher.dashboard');
        }

        return redirect()->route('dashboard');
    }
}