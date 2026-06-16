<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

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
            return view('event-ops.dashboard.event-ops-dashboard');
        } elseif (str_contains($email, 'admin')) {
            return redirect()->route('admin-dashboard');
        }

        return redirect()->route('nominator-dashboard');
    }
}
