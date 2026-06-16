<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Models\Events;
use Carbon\Carbon;

class NominatorController extends Controller
{
    public function dashboard()
    {
        $userId = Auth::id();

        // 1. Get counts
        $newEventsCount = Events::where('status', 'ongoing')->count();

        $nominationsCount = DB::table('nominees_master')
            ->where('nominator_id', $userId)
            ->count();

        $eventHistoryCount = Events::where('status', 'completed')->count();

        // 2. Get latest active events for the table
        $rawEvents = Events::where('status', 'ongoing')
            ->orderBy('created_at', 'desc')
            ->limit(5)
            ->get();

        $events = $rawEvents->map(function ($event) use ($userId) {
            // Get nominees count submitted by this nominator
            $event->nominees_count = DB::table('nominees_master')
                ->where('event_id', $event->id)
                ->where('nominator_id', $userId)
                ->count();

            // Format dates matching the reference "Dec 1, 2024"
            $event->formatted_start_date = Carbon::parse($event->start_date)->format('M j, Y');
            $event->formatted_end_date = Carbon::parse($event->end_date)->format('M j, Y');
            return $event;
        });

        return view('nominator.dashboard.nominator-dashboard', compact(
            'newEventsCount',
            'nominationsCount',
            'eventHistoryCount',
            'events'
        ));
    }

    public function activeEvents()
    {
        $userId = Auth::id();

        // Fetch ongoing events
        $rawEvents = Events::where('status', 'ongoing')
            ->orderBy('created_at', 'desc')
            ->get();

        $events = $rawEvents->map(function ($event) use ($userId) {
            $event->nominees_count = DB::table('nominees_master')
                ->where('event_id', $event->id)
                ->where('nominator_id', $userId)
                ->count();

            // Fetch actual nominee list for the view list modal
            $event->nominees = DB::table('nominees_master')
                ->where('event_id', $event->id)
                ->where('nominator_id', $userId)
                ->get()
                ->map(function ($nominee) {
                    $nominee->full_name = trim($nominee->first_name . ' ' . $nominee->last_name);
                    return $nominee;
                });

            // Date formats
            $event->formatted_start_date = Carbon::parse($event->start_date)->format('M j, Y');
            $event->formatted_end_date = Carbon::parse($event->end_date)->format('M j, Y');
            $event->formatted_deadline = Carbon::parse($event->nomination_deadline)->format('M j, Y H:i');

            // Determine closing soon (e.g. deadline within next 48 hours)
            $deadline = Carbon::parse($event->nomination_deadline);
            $event->is_closing_soon = $deadline->isFuture() && $deadline->diffInHours(Carbon::now()) <= 48;

            return $event;
        });

        // Calculate badges
        $openCount = Events::where('status', 'ongoing')->count();

        $closingSoonCount = 0;
        foreach ($events as $evt) {
            if ($evt->is_closing_soon) {
                $closingSoonCount++;
            }
        }

        return view('nominator.events.active', compact('events', 'openCount', 'closingSoonCount'));
    }

    public function completedEvents()
    {
        $userId = Auth::id();

        // Fetch completed events
        $rawEvents = Events::where('status', 'completed')
            ->orderBy('created_at', 'desc')
            ->get();

        $events = $rawEvents->map(function ($event) use ($userId) {
            $event->nominees_count = DB::table('nominees_master')
                ->where('event_id', $event->id)
                ->where('nominator_id', $userId)
                ->count();

            $event->nominees = DB::table('nominees_master')
                ->where('event_id', $event->id)
                ->where('nominator_id', $userId)
                ->get()
                ->map(function ($nominee) {
                    $nominee->full_name = trim($nominee->first_name . ' ' . $nominee->last_name);
                    return $nominee;
                });

            $event->formatted_start_date = Carbon::parse($event->start_date)->format('M j, Y');
            $event->formatted_end_date = Carbon::parse($event->end_date)->format('M j, Y');
            return $event;
        });

        return view('nominator.events.completed', compact('events'));
    }

    public function nominations()
    {
        $userId = Auth::id();

        // Fetch all nominees submitted by this nominator
        $nominees = DB::table('nominees_master')
            ->join('events', 'nominees_master.event_id', '=', 'events.id')
            ->where('nominees_master.nominator_id', $userId)
            ->select('nominees_master.*', 'events.name as event_name')
            ->orderBy('nominees_master.created_at', 'desc')
            ->get()
            ->map(function ($nominee) {
                $nominee->full_name = trim($nominee->first_name . ' ' . $nominee->last_name);
                return $nominee;
            });

        return view('nominator.nominations', compact('nominees'));
    }

    public function showNominationForm($id)
    {
        $event = Events::findOrFail($id);
        return view('nominator.events.nominate', compact('event'));
    }

    public function submitNomination(Request $request, $id)
    {
        $event = Events::findOrFail($id);
        $userId = Auth::id();

        // 1. Check if the nominator has already reached the nomination limit for this event
        $currentCount = DB::table('nominees_master')
            ->where('event_id', $event->id)
            ->where('nominator_id', $userId)
            ->count();

        if ($currentCount >= $event->max_nominees_per_form) {
            return redirect()->back()
                ->withErrors(['limit' => 'You have reached the maximum nominee limit (' . $event->max_nominees_per_form . ') for this event.'])
                ->withInput();
        }

        // 2. Validate form inputs
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

        // 3. Map GDPR compliance logic and client_or_prospect
        $clientOrProspect = 'Client';
        if (str_contains(strtolower($validated['gdpr_compliance']), 'prospect')) {
            $clientOrProspect = 'Prospect';
        }

        // 4. Save record
        DB::table('nominees_master')->insert([
            'event_id' => $event->id,
            'nominator_id' => $userId,
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
            'industry' => 'Technology', // Default required column not present in UI
            'country' => $validated['country'],
            'approval_status' => 'pending',
            'invite_status' => 'Invite Pending',
            'delivery_status' => 'Sent',
            'is_dnc_contact' => false,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        return redirect()->route('nominator-active-events')->with('success', 'Nomination submitted successfully.');
    }
}
