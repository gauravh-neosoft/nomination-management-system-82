<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Artisan;
use App\Models\Events;
use App\Models\GdprCompliance;

class EventsAdminController extends Controller
{
    public function create()
    {
        $gdprOptions = GdprCompliance::where('is_active', true)->orderBy('name', 'asc')->get();
        return view('admin.event-management', compact('gdprOptions'));
    }

    public function store(Request $request)
    {
        // Programmatically run migrations in case new columns are not migrated yet
        Artisan::call('migrate', ['--force' => true]);

        $rules = [
            'event_code' => 'required|string|max:100|unique:events,event_code',
            'name' => 'required|string|max:255',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after_or_equal:start_date',
            'location' => 'required|string|max:255',
            'type' => 'required|string|in:Hospitality,Non-Hospitality',
            'nomination_deadline' => 'required|date',
            'nomination_limit' => 'required|integer|min:1',
            'gdpr_compliance' => 'required|string|max:255',
        ];

        if ($request->input('type') === 'Hospitality') {
            $rules['declaration'] = 'required|string';
            $rules['invite_for'] = 'required|string|max:255';
            $rules['invite_spouser'] = 'required|string|max:255';
            $rules['govt_company'] = 'required|string|max:255';
        }

        $validated = $request->validate($rules);

        $event = new Events();
        $event->event_code = $validated['event_code'];
        $event->name = $validated['name'];
        $event->start_date = $validated['start_date'];
        $event->end_date = $validated['end_date'];
        $event->location = $validated['location'];
        $event->type = $validated['type'] === 'Hospitality' ? 'hospitality' : 'non_hospitality';
        $event->nomination_deadline = $validated['nomination_deadline'];
        $event->max_nominees_per_form = $validated['nomination_limit'];
        $event->gdpr_compliance = $validated['gdpr_compliance'];

        if ($event->type === 'hospitality') {
            $event->declaration = $validated['declaration'];
            $event->invite_for = $validated['invite_for'];
            $event->invite_spouser = $validated['invite_spouser'];
            $event->govt_company = $validated['govt_company'];
        }

        $event->status = 'ongoing';
        $event->last_updated_by = auth()->id();

        $event->save();

        return redirect()->route('admin-new-event-form')->with('success', 'Event stored successfully.');
    }
}
