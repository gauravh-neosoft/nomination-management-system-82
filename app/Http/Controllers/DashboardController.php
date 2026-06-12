<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index()
    {
        // dd(Auth::user());
        $data = [];
        return view('nominator.dashboard.nominator-dashboard', $data);
    }
}
