<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

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
        // Programmatically run migrations in case new columns are not migrated yet
        \Illuminate\Support\Facades\Artisan::call('migrate', ['--force' => true]);

        // Seed default events if events table is empty
        if (\App\Models\Events::count() === 0) {
            $defaults = [
                [
                    'event_code' => 'EVT-Q3-AWARDS',
                    'name' => 'Q3 Performance Awards',
                    'start_date' => '2026-12-01',
                    'end_date' => '2026-12-01',
                    'location' => 'Main Auditorium',
                    'type' => 'hospitality',
                    'nomination_deadline' => '2026-11-25 18:00:00',
                    'max_nominees_per_form' => 5,
                    'gdpr_compliance' => 'Existing Business Relationship (Client)',
                    'declaration' => 'I agree to the terms',
                    'invite_for' => 'Award Ceremony',
                    'invite_spouser' => 'Yes',
                    'govt_company' => 'No',
                    'status' => 'ongoing'
                ],
                [
                    'event_code' => 'EVT-TECH-SUMMIT',
                    'name' => 'Tech Summit 2026',
                    'start_date' => '2026-12-15',
                    'end_date' => '2026-12-17',
                    'location' => 'Convention Center',
                    'type' => 'non_hospitality',
                    'nomination_deadline' => '2026-12-10 18:00:00',
                    'max_nominees_per_form' => 15,
                    'gdpr_compliance' => 'Legitimate Business Interest(Prospect)',
                    'status' => 'ongoing'
                ],
                [
                    'event_code' => 'EVT-LEAD-RETREAT',
                    'name' => 'Leadership Retreat',
                    'start_date' => '2027-01-10',
                    'end_date' => '2027-01-12',
                    'location' => 'Executive Lodge',
                    'type' => 'hospitality',
                    'nomination_deadline' => '2027-01-05 18:00:00',
                    'max_nominees_per_form' => 10,
                    'gdpr_compliance' => 'Existing Business Relationship (Client)',
                    'declaration' => 'I agree to the privacy policy',
                    'invite_for' => 'Executive Strategic Session',
                    'invite_spouser' => 'Yes',
                    'govt_company' => 'No',
                    'status' => 'ongoing'
                ]
            ];
            foreach ($defaults as $d) {
                \App\Models\Events::create($d);
            }
        }

        $events = \App\Models\Events::withTrashed()->orderBy('created_at', 'desc')->get();
        return view('admin.events', compact('events'));
    }

    public function destroy($id)
    {
        $event = \App\Models\Events::findOrFail($id);
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
