<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Models\User;

class LoginController extends Controller
{
    public function login(Request $request)
    {
        $email = $request->input('user_email', 'gaurav@nominator.com');

        // Determine the role based on the email domain / prefix
        $roleName = 'nominator';
        if (str_contains($email, 'admin')) {
            $roleName = 'admin';
        } elseif (str_contains($email, 'eventops')) {
            $roleName = 'event_ops';
        } elseif (str_contains($email, 'unitspoc')) {
            $roleName = 'unit_spoc';
        }

        // Fetch corresponding role ID
        $roleId = DB::table('roles')->where('name', $roleName)->value('id');

        // Find or dynamically update/create the user to simulate SSO login with the correct role
        $user = User::updateOrCreate(
            ['email' => $email],
            [
                'name' => ucwords(current(explode('@', $email))),
                'password' => bcrypt('test@123'),
                'role_id' => $roleId
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
        return redirect()->route('login');
    }
}
