<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use App\Models\Events;
use Carbon\Carbon;

class UnitSpocController extends Controller
{
    /**
     * Display the Unit SPOC Dashboard with metrics and event tabs.
     */
    public function dashboard()
    {
        try {
            // 1. Fetch counts
            $totalNominations = DB::table('nominees_master')->count();
            $totalPending = DB::table('nominees_master')->where('approval_status', 'pending')->count();
            
            // Reviewed today: approved or rejected today
            $reviewedToday = DB::table('nominees_master')
                ->whereIn('approval_status', ['approved', 'rejected'])
                ->whereDate('updated_at', Carbon::today())
                ->count();

            // Recent activities
            $recentActivities = DB::table('nominees_master')
                ->join('events', 'nominees_master.event_id', '=', 'events.id')
                ->whereIn('nominees_master.approval_status', ['approved', 'rejected'])
                ->select('nominees_master.*', 'events.name as event_name')
                ->orderBy('nominees_master.updated_at', 'desc')
                ->limit(3)
                ->get()
                ->map(function ($activity) {
                    $activity->time_diff = Carbon::parse($activity->updated_at)->diffForHumans();
                    return $activity;
                });

            // 2. Fetch current/ongoing events
            $currentEvents = Events::where('status', 'ongoing')
                ->orderBy('created_at', 'desc')
                ->get()
                ->map(function ($event) {
                    // Find a nominator assigned to this event to display in the nominator column
                    $nominator = DB::table('event_assignments')
                        ->join('users', 'event_assignments.user_id', '=', 'users.id')
                        ->where('event_assignments.event_id', $event->id)
                        ->select(DB::raw("CONCAT(users.name, ' ', SUBSTRING(users.last_name, 1, 1), '.') as short_name"))
                        ->value('short_name') ?? 'System';

                    $event->nominator_name = $nominator;
                    $event->nominees_count = DB::table('nominees_master')
                        ->where('event_id', $event->id)
                        ->count();
                    $event->formatted_start_date = Carbon::parse($event->start_date)->format('M j, Y');
                    $event->formatted_end_date = Carbon::parse($event->end_date)->format('M j, Y');
                    
                    // closing soon determination (e.g. within 15 days of start date)
                    $startDate = Carbon::parse($event->start_date);
                    $diffInDays = Carbon::now()->diffInDays($startDate, false);
                    $event->is_closing_soon = $diffInDays >= 0 && $diffInDays <= 15;
                    
                    return $event;
                });

            // 3. Fetch events with pending nominations
            $pendingNominationsEvents = DB::table('nominees_master')
                ->join('events', 'nominees_master.event_id', '=', 'events.id')
                ->join('users', 'nominees_master.nominator_id', '=', 'users.id')
                ->where('nominees_master.approval_status', 'pending')
                ->select(
                    'events.id as event_id',
                    'events.name as event_name',
                    'events.type as event_type',
                    'events.status as event_status',
                    'events.event_code',
                    'events.start_date',
                    'events.end_date',
                    'events.nomination_deadline',
                    'users.id as nominator_id',
                    'users.name as nominator_name',
                    'users.last_name as nominator_last_name',
                    DB::raw('count(nominees_master.id) as pending_count')
                )
                ->groupBy(
                    'events.id',
                    'events.name',
                    'events.type',
                    'events.status',
                    'events.event_code',
                    'events.start_date',
                    'events.end_date',
                    'events.nomination_deadline',
                    'users.id',
                    'users.name',
                    'users.last_name'
                )
                ->get()
                ->map(function ($event) {
                    $event->formatted_start_date = Carbon::parse($event->start_date)->format('M j, Y');
                    $event->formatted_end_date = Carbon::parse($event->end_date)->format('M j, Y');
                    
                    $startDate = Carbon::parse($event->start_date);
                    $diffInDays = Carbon::now()->diffInDays($startDate, false);
                    $event->is_closing_soon = $diffInDays >= 0 && $diffInDays <= 15;
                    
                    // Fetch nominee list for this event and nominator
                    $event->nominees = DB::table('nominees_master')
                        ->where('event_id', $event->event_id)
                        ->where('nominator_id', $event->nominator_id)
                        ->where('approval_status', 'pending')
                        ->get()
                        ->map(function ($nominee) {
                            $nominee->full_name = trim($nominee->first_name . ' ' . $nominee->last_name);
                            return $nominee;
                        });
                    
                    return $event;
                });

            return view('unit-spoc.dashboard.unit-spoc-dashboard', compact(
                'totalNominations',
                'totalPending',
                'reviewedToday',
                'currentEvents',
                'pendingNominationsEvents',
                'recentActivities'
            ));
        } catch (\Exception $e) {
            Log::error('Error loading Unit SPOC dashboard: ' . $e->getMessage() . "\n" . $e->getTraceAsString());
            return redirect()->back()->withErrors(['error' => 'An error occurred while loading the dashboard.']);
        }
    }

    /**
     * Display ongoing active events.
     */
    public function activeEvents()
    {
        try {
            // We use the same data retrieval logic as the dashboard for consistency
            $currentEvents = Events::where('status', 'ongoing')
                ->orderBy('created_at', 'desc')
                ->get()
                ->map(function ($event) {
                    $nominator = DB::table('event_assignments')
                        ->join('users', 'event_assignments.user_id', '=', 'users.id')
                        ->where('event_assignments.event_id', $event->id)
                        ->select(DB::raw("CONCAT(users.name, ' ', SUBSTRING(users.last_name, 1, 1), '.') as short_name"))
                        ->value('short_name') ?? 'System';

                    $event->nominator_name = $nominator;
                    $event->nominees_count = DB::table('nominees_master')
                        ->where('event_id', $event->id)
                        ->count();
                    $event->formatted_start_date = Carbon::parse($event->start_date)->format('M j, Y');
                    $event->formatted_end_date = Carbon::parse($event->end_date)->format('M j, Y');
                    
                    $startDate = Carbon::parse($event->start_date);
                    $diffInDays = Carbon::now()->diffInDays($startDate, false);
                    $event->is_closing_soon = $diffInDays >= 0 && $diffInDays <= 15;
                    
                    return $event;
                });

            $pendingNominationsEvents = DB::table('nominees_master')
                ->join('events', 'nominees_master.event_id', '=', 'events.id')
                ->join('users', 'nominees_master.nominator_id', '=', 'users.id')
                ->where('nominees_master.approval_status', 'pending')
                ->select(
                    'events.id as event_id',
                    'events.name as event_name',
                    'events.type as event_type',
                    'events.status as event_status',
                    'events.event_code',
                    'events.start_date',
                    'events.end_date',
                    'events.nomination_deadline',
                    'users.id as nominator_id',
                    'users.name as nominator_name',
                    'users.last_name as nominator_last_name',
                    DB::raw('count(nominees_master.id) as pending_count')
                )
                ->groupBy(
                    'events.id',
                    'events.name',
                    'events.type',
                    'events.status',
                    'events.event_code',
                    'events.start_date',
                    'events.end_date',
                    'events.nomination_deadline',
                    'users.id',
                    'users.name',
                    'users.last_name'
                )
                ->get()
                ->map(function ($event) {
                    $event->formatted_start_date = Carbon::parse($event->start_date)->format('M j, Y');
                    $event->formatted_end_date = Carbon::parse($event->end_date)->format('M j, Y');
                    
                    $startDate = Carbon::parse($event->start_date);
                    $diffInDays = Carbon::now()->diffInDays($startDate, false);
                    $event->is_closing_soon = $diffInDays >= 0 && $diffInDays <= 15;
                    
                    // Fetch nominee list for this event and nominator
                    $event->nominees = DB::table('nominees_master')
                        ->where('event_id', $event->event_id)
                        ->where('nominator_id', $event->nominator_id)
                        ->where('approval_status', 'pending')
                        ->get()
                        ->map(function ($nominee) {
                            $nominee->full_name = trim($nominee->first_name . ' ' . $nominee->last_name);
                            return $nominee;
                        });
                    
                    return $event;
                });

            // Count badges
            $openCount = 0;
            $closingSoonCount = 0;
            foreach ($currentEvents as $event) {
                if ($event->is_closing_soon) {
                    $closingSoonCount++;
                } else {
                    $openCount++;
                }
            }

            return view('unit-spoc.events.active', compact(
                'currentEvents',
                'pendingNominationsEvents',
                'openCount',
                'closingSoonCount'
            ));
        } catch (\Exception $e) {
            Log::error('Error loading active events page: ' . $e->getMessage() . "\n" . $e->getTraceAsString());
            return redirect()->back()->withErrors(['error' => 'An error occurred while loading active events.']);
        }
    }

    /**
     * Display completed events.
     */
    public function completedEvents()
    {
        try {
            $completedEvents = Events::where('status', 'completed')
                ->orderBy('created_at', 'desc')
                ->get()
                ->map(function ($event) {
                    $event->nominees_count = DB::table('nominees_master')
                        ->where('event_id', $event->id)
                        ->count();
                    $event->formatted_start_date = Carbon::parse($event->start_date)->format('M j, Y');
                    $event->formatted_end_date = Carbon::parse($event->end_date)->format('M j, Y');

                    // Fetch nominees for details modal
                    $event->nominees = DB::table('nominees_master')
                        ->where('event_id', $event->id)
                        ->get()
                        ->map(function ($nominee) {
                            $nominee->full_name = trim($nominee->first_name . ' ' . $nominee->last_name);
                            return $nominee;
                        });
                    return $event;
                });

            return view('unit-spoc.events.completed', compact('completedEvents'));
        } catch (\Exception $e) {
            Log::error('Error loading completed events page: ' . $e->getMessage() . "\n" . $e->getTraceAsString());
            return redirect()->back()->withErrors(['error' => 'An error occurred while loading completed events.']);
        }
    }

    /**
     * Display list of nominations.
     */
    public function nominations(Request $request)
    {
        try {
            $userId = Auth::id();

            // Fetch units/subunits/gdpr for edit modal dropdown lists
            $units = DB::table('units')->where('is_active', true)->pluck('name');
            $subUnits = DB::table('sub_units')->where('is_active', true)->pluck('name');
            $gdprOptions = DB::table('gdpr_compliances')->where('is_active', true)->pluck('name');

            // Build queries for 'By Nominator' and 'By Self'
            $byNominatorQuery = DB::table('nominees_master')
                ->join('events', 'nominees_master.event_id', '=', 'events.id')
                ->join('users', 'nominees_master.nominator_id', '=', 'users.id')
                ->where('nominees_master.nominator_id', '!=', $userId)
                ->select(
                    'nominees_master.*',
                    'events.name as event_name',
                    'users.name as nominator_name',
                    'users.last_name as nominator_last_name'
                );

            $bySelfQuery = DB::table('nominees_master')
                ->join('events', 'nominees_master.event_id', '=', 'events.id')
                ->join('users', 'nominees_master.nominator_id', '=', 'users.id')
                ->where('nominees_master.nominator_id', '=', $userId)
                ->select(
                    'nominees_master.*',
                    'events.name as event_name',
                    'users.name as nominator_name',
                    'users.last_name as nominator_last_name'
                );

            // Fetch records
            $byNominator = $byNominatorQuery->orderBy('nominees_master.created_at', 'desc')->get()->map(function ($nom) {
                $nom->full_name = trim($nom->first_name . ' ' . $nom->last_name);
                return $nom;
            });

            $bySelf = $bySelfQuery->orderBy('nominees_master.created_at', 'desc')->get()->map(function ($nom) {
                $nom->full_name = trim($nom->first_name . ' ' . $nom->last_name);
                return $nom;
            });

            return view('unit-spoc.nominations', compact('byNominator', 'bySelf', 'units', 'subUnits', 'gdprOptions'));
        } catch (\Exception $e) {
            Log::error('Error loading nominations page: ' . $e->getMessage() . "\n" . $e->getTraceAsString());
            return redirect()->back()->withErrors(['error' => 'An error occurred while loading nominations.']);
        }
    }

    /**
     * Approve a nomination.
     */
    public function approveNomination($id)
    {
        try {
            DB::table('nominees_master')->where('id', $id)->update([
                'approval_status' => 'approved',
                'unit_spoc_id' => Auth::id(),
                'updated_at' => now(),
            ]);

            return redirect()->back()->with('success', 'Nomination approved successfully.');
        } catch (\Exception $e) {
            Log::error('Error approving nomination ' . $id . ': ' . $e->getMessage() . "\n" . $e->getTraceAsString());
            return redirect()->back()->withErrors(['error' => 'Failed to approve nomination. Please try again.']);
        }
    }

    /**
     * Reject a nomination with optional remarks.
     */
    public function rejectNomination(Request $request, $id)
    {
        try {
            $validated = $request->validate([
                'remark' => 'nullable|string|max:1000',
            ]);

            DB::table('nominees_master')->where('id', $id)->update([
                'approval_status' => 'rejected',
                'spoc_comment' => $validated['remark'] ?? null,
                'unit_spoc_id' => Auth::id(),
                'updated_at' => now(),
            ]);

            return redirect()->back()->with('success', 'Nomination rejected successfully.');
        } catch (\Exception $e) {
            Log::error('Error rejecting nomination ' . $id . ': ' . $e->getMessage() . "\n" . $e->getTraceAsString());
            return redirect()->back()->withErrors(['error' => 'Failed to reject nomination. Please try again.']);
        }
    }

    /**
     * Add or update comment remark.
     */
    public function addRemark(Request $request, $id)
    {
        try {
            $validated = $request->validate([
                'remark' => 'required|string|max:1000',
            ]);

            DB::table('nominees_master')->where('id', $id)->update([
                'spoc_comment' => $validated['remark'],
                'updated_at' => now(),
            ]);

            return redirect()->back()->with('success', 'Remarks updated successfully.');
        } catch (\Exception $e) {
            Log::error('Error updating remarks for nomination ' . $id . ': ' . $e->getMessage() . "\n" . $e->getTraceAsString());
            return redirect()->back()->withErrors(['error' => 'Failed to update remarks. Please try again.']);
        }
    }

    /**
     * Update nominee record details.
     */
    public function updateNomination(Request $request, $id)
    {
        try {
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
                'last_updated_by' => Auth::id(),
                'updated_at' => now(),
            ]);

            return redirect()->back()->with('success', 'Nomination updated successfully.');
        } catch (\Exception $e) {
            Log::error('Error updating nomination ' . $id . ': ' . $e->getMessage() . "\n" . $e->getTraceAsString());
            return redirect()->back()->withErrors(['error' => 'Failed to update nomination. Please try again.']);
        }
    }
}
