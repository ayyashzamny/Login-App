<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Auth;

class LoginController extends Controller
{
    public function showLoginForm()
    {
        return view('auth.login');
    }

    public function login(Request $request)
    {
        $credentials = $request->only('email', 'password');

        if (Auth::attempt($credentials)) {
            $request->session()->regenerate();

            $user = Auth::user(); // Fetch the authenticated user

            if ($user->is_admin) {
                return redirect('/admin/dashboard');
            }

            return redirect()->intended('/'); // Redirect to the user's home page
        }

        return back()->withErrors([
            'email' => 'The provided credentials do not match our records.',
        ]);
    }


    public function logout(Request $request)
    {
        Auth::logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        return redirect('/');
    }

    // protected function authenticated(Request $request, $user)
    // {
    //     if ($user->is_admin) {
    //         return redirect('/admin/dashboard');
    //     }

    //     return redirect('/admin/dashboard');
    // }

}
