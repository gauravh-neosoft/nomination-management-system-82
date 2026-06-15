<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Events;
use Illuminate\Http\Request;
use Carbon\Carbon;

class AdminController extends Controller
{
    public function dashboard()
    {
        return view('admin.dashboard.admin-dashboard');
    }

    public function roles()
    {
        return view('admin.roles');
    }

    public function users()
    {
        return view('admin.users');
    }

    public function events()
    {
        // Seed default events if events table is empty

        $events = Events::orderBy('created_at', 'desc')->get()->map(function ($event) {
            $event->start_date = Carbon::parse($event->start_date)->format('d-m-Y');
            $event->end_date = Carbon::parse($event->end_date)->format('d-m-Y');
            $event->nomination_deadline = Carbon::parse($event->nomination_deadline)->format('d-m-Y');
            return $event;
        });

        // dd($events);
        return view('admin.events', compact('events'));
    }

    public function destroy($id)
    {
        $event = Events::findOrFail($id);
        $event->delete();

        return response()->json(['success' => true]);
    }

    public function queue()
    {
        return view('admin.queue');
    }

    public function contacts()
    {
        return view('admin.contacts');
    }

    public function exclusion()
    {
        return view('admin.exclusion');
    }

    public function mdm()
    {
        return view('admin.mdm');
    }

    public function cms()
    {
        return view('admin.cms');
    }

    public function reports()
    {
        return view('admin.reports');
    }

    public function domain()
    {
        return view('admin.domain');
    }
}
