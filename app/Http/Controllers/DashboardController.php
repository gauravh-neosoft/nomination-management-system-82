<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        if (!$user) {
            return redirect()->route('login');
        }

        $email = $user->email;

        if (str_contains($email, 'unitspoc')) {
            return view('unit-spoc.dashboard.unit-spoc-dashboard');
        } elseif (str_contains($email, 'eventops')) {
            return redirect()->route('event-ops-dashboard');
        } elseif (str_contains($email, 'admin')) {
            return redirect()->route('admin-dashboard');
        }

        return redirect()->route('nominator-dashboard');
    }

    public function profile()
    {
        $user = Auth::user();
        if (!$user) {
            return redirect()->route('login');
        }

        $userId = $user->id;
        $roleId = $user->role_id;
        $roleName = DB::table('roles')->where('id', $roleId)->value('display_name') ?? 'Nominator';

        // Check if user is nominator or not
        if (strtolower($roleName) === 'nominator') {
            $nominationsCount = DB::table('nominees_master')
                ->where('nominator_id', $userId)
                ->count();
            $approvedCount = DB::table('nominees_master')
                ->where('nominator_id', $userId)
                ->where('approval_status', 'approved')
                ->count();
            $pendingCount = DB::table('nominees_master')
                ->where('nominator_id', $userId)
                ->where('approval_status', 'pending')
                ->count();
        } else {
            $nominationsCount = DB::table('nominees_master')->count();
            $approvedCount = DB::table('nominees_master')->where('approval_status', 'approved')->count();
            $pendingCount = DB::table('nominees_master')->where('approval_status', 'pending')->count();
        }

        return view('profile', compact('user', 'roleName', 'nominationsCount', 'approvedCount', 'pendingCount'));
    }
}
