<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Hash;

class RegisterController extends Controller
{
    // @desc Show the registration form
    // @route GET /register
    public function register(): View
    {

        return view('auth.register');
    }

    // @desc Store a newly registered user to the database
    // @route POST /register
    public function store(Request $request): RedirectResponse
    {
        $validatedData = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8|confirmed',
        ]);

        // Hash Password
        $validatedData['password'] = Hash::make($validatedData['password']);

        // Create User
        $user = User::create($validatedData);

        return redirect()->route('login')->with('success', 'Registration successful. Please login.');
    }
}
