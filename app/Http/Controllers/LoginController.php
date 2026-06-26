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
        if (str_contains($email, 'super_admin')) {
            $roleName = 'super_admin';
        } elseif (str_contains($email, 'admin')) {
            $roleName = 'admin';
        } elseif (str_contains($email, 'event_ops') || str_contains($email, 'eventops')) {
            $roleName = 'event_ops';
        } elseif (str_contains($email, 'unit_spoc') || str_contains($email, 'unitspoc')) {
            $roleName = 'unit_spoc';
        }

        // Fetch corresponding role ID
        $roleId = DB::table('roles')->where('name', $roleName)->value('id');

        // Parse names from the email
        $parts = explode('@', $email);
        $fullName = ucwords(current($parts));
        $nameParts = explode('.', $fullName);
        $firstName = $nameParts[0] ?? $fullName;
        $lastName = $nameParts[1] ?? 'SSO';

        // Check if the user already exists and is inactive
        $existingUser = User::where('email', $email)->first();
        if ($existingUser && $existingUser->status == 0) {
            return redirect()->back()->withErrors(['user_email' => 'Your account is inactive. Please contact the administrator.']);
        }

        // Find or dynamically update/create the user to simulate SSO login with the correct role
        $user = User::updateOrCreate(
            ['email' => $email],
            [
                'name' => $firstName,
                'last_name' => $lastName,
                'contact_no' => '+1234567890',
                'status' => 1,
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
