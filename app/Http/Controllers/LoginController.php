<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;

class LoginController extends Controller
{
    // @desc Show the login form
    // @route GET /login
    public function login(): View
    {

        return view('auth.login');
    }

    // @desc Authenticate user and log them in
    // @route POST /login
    public function authenticate(Request $request): RedirectResponse
    {
        $credentials = $request->validate([
            'email' => 'required|string|email|max:255',
            'password' => 'required|string',
        ]);

        // Attempt to authenticate the user
        if (Auth::attempt($credentials)) {
            // Regenerate session to prevent fixation
            $request->session()->regenerate();

            return redirect()->intended(route('home'))->with('success', 'You are logged in!');
        }

        // Authentication failed, redirect back with error message
        return back()->withErrors([
            'email' => 'The provided credentials do not match our records.',
        ])->onlyInput('email');
    }

    // @desc Logout the user
    // @route POST /logout
    public function logout(Request $request): RedirectResponse
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/');
    }
}
