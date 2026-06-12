<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;

class LoginController extends Controller
{
    public function login(Request $request)
    {
        $email = $request->input('user_email', 'gaurav@nominator.com');

        // Find or dynamically create the user to simulate SSO login
        $user = User::firstOrCreate(
            ['email' => $email],
            [
                'name' => ucwords(current(explode('@', $email))),
                'password' => bcrypt('test@123')
            ]
        );

        Auth::login($user);

        return redirect('dashboard');
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect()->route('login_page');
    }
}
