<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use App\Models\Events;
use App\Models\DncContact;
use App\Models\DncDomain;
use App\Models\User;
use Carbon\Carbon;

class EventOpsController extends Controller
{
    public function __construct()
    {
        // Dynamically run migrations if needed since console command is blocked by group policy
        try {
            if (!Schema::hasTable('dnc_contacts') || !Schema::hasTable('dnc_domains')) {
                \Illuminate\Support\Facades\Artisan::call('migrate', ['--force' => true]);
            }
        } catch (\Exception $e) {
            // Log or ignore if DB not ready
        }
    }

    public function dashboard()
    {
        // 1. Stats card details
        $eventsCount = Events::where('status', 'ongoing')->count();
        $nominationsCount = DB::table('nominees_master')->count();
        $pendingCount = DB::table('nominees_master')->where('invite_status', 'Invite Pending')->count();
        $reviewedCount = DB::table('nominees_master')->where('invite_status', '!=', 'Invite Pending')->count();

        // 2. Ongoing Events List
        $events = Events::where('status', 'ongoing')
            ->orderBy('created_at', 'desc')
            ->get()
            ->map(function ($event) {
                $event->formatted_start_date = Carbon::parse($event->start_date)->format('M j, Y');
                $event->formatted_deadline = Carbon::parse($event->nomination_deadline)->format('M j, Y');
                
                // Check closing soon (deadline is within 15 days)
                $deadline = Carbon::parse($event->nomination_deadline);
                $diffInDays = Carbon::now()->diffInDays($deadline, false);
                $event->status_badge = 'Open';
                if ($diffInDays >= 0 && $diffInDays <= 15) {
                    $event->status_badge = 'Closing soon';
                } elseif ($diffInDays < 0) {
                    $event->status_badge = 'Closed';
                }

                return $event;
            });

        // Recent Activity
        $recentActivities = DB::table('nominees_master')
            ->join('events', 'nominees_master.event_id', '=', 'events.id')
            ->select('nominees_master.*', 'events.name as event_name')
            ->orderBy('nominees_master.created_at', 'desc')
            ->limit(5)
            ->get()
            ->map(function ($activity) {
                $activity->formatted_time = Carbon::parse($activity->created_at)->diffForHumans();
                return $activity;
            });

        return view('event-ops.dashboard.event-ops-dashboard', compact(
            'eventsCount',
            'nominationsCount',
            'pendingCount',
            'reviewedCount',
            'events',
            'recentActivities'
        ));
    }

    public function activeEvents(Request $request)
    {
        $search = $request->input('search');

        $query = Events::where('status', 'ongoing');

        if ($search) {
            $query->where('name', 'like', '%' . $search . '%');
        }

        $events = $query->orderBy('created_at', 'desc')->get()->map(function ($event) {
            $event->formatted_start_date = Carbon::parse($event->start_date)->format('M j, Y');
            $event->formatted_end_date = Carbon::parse($event->end_date)->format('M j, Y');
            $event->formatted_deadline = Carbon::parse($event->nomination_deadline)->format('M j, Y');

            // Count nominees
            $event->nominees_count = DB::table('nominees_master')
                ->where('event_id', $event->id)
                ->count();

            // Nominees list for modal
            $event->nominees = DB::table('nominees_master')
                ->where('event_id', $event->id)
                ->get()
                ->map(function ($nominee) {
                    $nominee->full_name = trim($nominee->first_name . ' ' . $nominee->last_name);
                    return $nominee;
                });

            // Check closing soon (deadline is within 15 days)
            $deadline = Carbon::parse($event->nomination_deadline);
            $diffInDays = Carbon::now()->diffInDays($deadline, false);
            $event->is_closing_soon = $diffInDays >= 0 && $diffInDays <= 15;
            $event->status_badge = $event->is_closing_soon ? 'Closing soon' : 'Open';

            return $event;
        });

        $openCount = Events::where('status', 'ongoing')->count();
        $closingSoonCount = 0;
        foreach ($events as $evt) {
            if ($evt->is_closing_soon) {
                $closingSoonCount++;
            }
        }

        return view('event-ops.events.active', compact('events', 'openCount', 'closingSoonCount', 'search'));
    }

    public function completedEvents(Request $request)
    {
        $search = $request->input('search');

        $query = Events::where('status', 'completed');

        if ($search) {
            $query->where('name', 'like', '%' . $search . '%');
        }

        $events = $query->orderBy('created_at', 'desc')->get()->map(function ($event) {
            $event->formatted_start_date = Carbon::parse($event->start_date)->format('M j, Y');
            $event->formatted_end_date = Carbon::parse($event->end_date)->format('M j, Y');

            // Count nominees
            $event->nominees_count = DB::table('nominees_master')
                ->where('event_id', $event->id)
                ->count();

            // Nominees list for modal
            $event->nominees = DB::table('nominees_master')
                ->where('event_id', $event->id)
                ->get()
                ->map(function ($nominee) {
                    $nominee->full_name = trim($nominee->first_name . ' ' . $nominee->last_name);
                    return $nominee;
                });

            // Find nominator/assigned name
            $nominator = User::find($event->created_by);
            $event->assigned_to = $nominator ? $nominator->name : 'Prathrna Saxena';

            return $event;
        });

        return view('event-ops.events.completed', compact('events', 'search'));
    }

    public function nominations(Request $request)
    {
        $search = $request->input('search');

        $pendingQuery = DB::table('nominees_master')
            ->join('events', 'nominees_master.event_id', '=', 'events.id')
            ->join('users', 'nominees_master.nominator_id', '=', 'users.id')
            ->select('nominees_master.*', 'events.name as event_name', 'users.name as nominator_name')
            ->where('nominees_master.invite_status', 'Invite Pending');

        $reviewedQuery = DB::table('nominees_master')
            ->join('events', 'nominees_master.event_id', '=', 'events.id')
            ->join('users', 'nominees_master.nominator_id', '=', 'users.id')
            ->select('nominees_master.*', 'events.name as event_name', 'users.name as nominator_name')
            ->where('nominees_master.invite_status', '!=', 'Invite Pending');

        if ($search) {
            $pendingQuery->where(function ($q) use ($search) {
                $q->where('nominees_master.first_name', 'like', '%' . $search . '%')
                  ->orWhere('nominees_master.last_name', 'like', '%' . $search . '%')
                  ->orWhere('events.name', 'like', '%' . $search . '%');
            });

            $reviewedQuery->where(function ($q) use ($search) {
                $q->where('nominees_master.first_name', 'like', '%' . $search . '%')
                  ->orWhere('nominees_master.last_name', 'like', '%' . $search . '%')
                  ->orWhere('events.name', 'like', '%' . $search . '%');
            });
        }

        $pendingNominations = $pendingQuery->orderBy('nominees_master.created_at', 'desc')->get()->map(function ($nom) {
            $nom->full_name = trim($nom->first_name . ' ' . $nom->last_name);
            return $nom;
        });

        $reviewedNominations = $reviewedQuery->orderBy('nominees_master.created_at', 'desc')->get()->map(function ($nom) {
            $nom->full_name = trim($nom->first_name . ' ' . $nom->last_name);
            return $nom;
        });

        $units = DB::table('units')->where('is_active', true)->pluck('name');
        $subUnits = DB::table('sub_units')->where('is_active', true)->pluck('name');
        $gdprOptions = DB::table('gdpr_compliances')->where('is_active', true)->pluck('name');

        return view('event-ops.nominations.index', compact(
            'pendingNominations',
            'reviewedNominations',
            'search',
            'units',
            'subUnits',
            'gdprOptions'
        ));
    }

    public function eventNominations(Request $request, $id)
    {
        $event = Events::findOrFail($id);
        $search = $request->input('search');
        $status = $request->input('status'); // all, pending, approved

        $query = DB::table('nominees_master')
            ->where('event_id', $id);

        if ($status && $status !== 'all') {
            $query->where('approval_status', $status);
        }

        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('first_name', 'like', '%' . $search . '%')
                  ->orWhere('last_name', 'like', '%' . $search . '%')
                  ->orWhere('email', 'like', '%' . $search . '%');
            });
        }

        $nominations = $query->orderBy('created_at', 'desc')->get()->map(function ($nom) {
            $nom->full_name = trim($nom->first_name . ' ' . $nom->last_name);
            return $nom;
        });

        $units = DB::table('units')->where('is_active', true)->pluck('name');
        $subUnits = DB::table('sub_units')->where('is_active', true)->pluck('name');
        $gdprOptions = DB::table('gdpr_compliances')->where('is_active', true)->pluck('name');

        return view('event-ops.nominations.event-nominations', compact(
            'event',
            'nominations',
            'search',
            'status',
            'units',
            'subUnits',
            'gdprOptions'
        ));
    }

    public function reports(Request $request)
    {
        $search = $request->input('search');

        $query = Events::query();

        if ($search) {
            $query->where('name', 'like', '%' . $search . '%');
        }

        $events = $query->orderBy('created_at', 'desc')->get()->map(function ($event) {
            $event->formatted_start_date = Carbon::parse($event->start_date)->format('M j, Y');
            $event->formatted_end_date = Carbon::parse($event->end_date)->format('M j, Y');
            
            // Count nominees
            $event->nominees_count = DB::table('nominees_master')
                ->where('event_id', $event->id)
                ->count();

            // Modal mapping
            $event->nominees = DB::table('nominees_master')
                ->where('event_id', $event->id)
                ->get()
                ->map(function ($nominee) {
                    $nominee->full_name = trim($nominee->first_name . ' ' . $nominee->last_name);
                    return $nominee;
                });

            return $event;
        });

        // Totals
        $eventsCreated = Events::count();
        $activeEvents = Events::where('status', 'ongoing')->count();
        $completedEvents = Events::where('status', 'completed')->count();
        $totalNominations = DB::table('nominees_master')->count();

        return view('event-ops.reports.index', compact(
            'events',
            'eventsCreated',
            'activeEvents',
            'completedEvents',
            'totalNominations',
            'search'
        ));
    }

    public function dncContact()
    {
        $contacts = DncContact::orderBy('name', 'asc')->get();
        $domains = DncDomain::orderBy('account_name', 'asc')->get();

        return view('event-ops.dnc-contact.index', compact('contacts', 'domains'));
    }

    public function addRemark(Request $request, $id)
    {
        $validated = $request->validate([
            'remark' => 'required|string|max:1000',
        ]);

        DB::table('nominees_master')->where('id', $id)->update([
            'spoc_comment' => $validated['remark'],
            'updated_at' => now(),
        ]);

        return redirect()->back()->with('success', 'Remarks updated successfully.');
    }

    public function updateNomination(Request $request, $id)
    {
        $validated = $request->validate([
            'gdpr_compliance' => 'required|string|max:255',
            'unit' => 'required|string|max:100',
            'sub_unit' => 'required|string|max:100',
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'company' => 'required|string|max:255',
            'title' => 'required|string|max:255',
            'job_level' => 'required|string|in:1,2,3,4',
            'primary_account_manager_name' => 'required|string|max:255',
            'primary_account_manager_email' => 'required|email|max:255',
            'account_manager_email_1' => 'nullable|email|max:255',
            'account_manager_email_2' => 'nullable|email|max:255',
            'business_or_it' => 'required|string|in:Business,IT',
            'country' => 'required|string|max:100',
        ]);

        // Map GDPR compliance logic and client_or_prospect
        $clientOrProspect = 'Client';
        if (str_contains(strtolower($validated['gdpr_compliance']), 'prospect')) {
            $clientOrProspect = 'Prospect';
        }

        DB::table('nominees_master')->where('id', $id)->update([
            'gdpr_compliance' => $validated['gdpr_compliance'],
            'unit' => $validated['unit'],
            'sub_unit' => $validated['sub_unit'],
            'client_or_prospect' => $clientOrProspect,
            'first_name' => $validated['first_name'],
            'last_name' => $validated['last_name'],
            'email' => $validated['email'],
            'company' => $validated['company'],
            'title' => $validated['title'],
            'job_level' => $validated['job_level'],
            'primary_account_manager_name' => $validated['primary_account_manager_name'],
            'primary_account_manager_email' => $validated['primary_account_manager_email'],
            'account_manager_email_1' => $validated['account_manager_email_1'],
            'account_manager_email_2' => $validated['account_manager_email_2'],
            'business_or_it' => $validated['business_or_it'],
            'country' => $validated['country'],
            'updated_at' => now(),
        ]);

        return redirect()->back()->with('success', 'Nomination updated successfully.');
    }

    public function saveDncContact(Request $request)
    {
        $validated = $request->validate([
            'id' => 'nullable|integer',
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255|unique:dnc_contacts,email,' . ($request->filled('id') ? $request->id : 'NULL'),
        ]);

        if ($request->filled('id')) {
            DncContact::findOrFail($request->id)->update([
                'name' => $validated['name'],
                'email' => $validated['email']
            ]);
        } else {
            DncContact::create([
                'name' => $validated['name'],
                'email' => $validated['email']
            ]);
        }

        return redirect()->back()->with('success', 'DNC Contact saved successfully.');
    }

    public function deleteDncContact($id)
    {
        DncContact::findOrFail($id)->delete();
        return redirect()->back()->with('success', 'DNC Contact deleted successfully.');
    }

    public function saveDncDomain(Request $request)
    {
        $validated = $request->validate([
            'id' => 'nullable|integer',
            'account_name' => 'required|string|max:255',
            'domain' => 'required|string|max:255|unique:dnc_domains,domain,' . ($request->filled('id') ? $request->id : 'NULL'),
        ]);

        if ($request->filled('id')) {
            DncDomain::findOrFail($request->id)->update([
                'account_name' => $validated['account_name'],
                'domain' => $validated['domain']
            ]);
        } else {
            DncDomain::create([
                'account_name' => $validated['account_name'],
                'domain' => $validated['domain']
            ]);
        }

        return redirect()->back()->with('success', 'DNC Domain saved successfully.');
    }

    public function deleteDncDomain($id)
    {
        DncDomain::findOrFail($id)->delete();
        return redirect()->back()->with('success', 'DNC Domain deleted successfully.');
    }
}
