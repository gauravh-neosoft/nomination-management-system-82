<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Auth;
use App\Models\Events;
use App\Models\GdprCompliance;
use Illuminate\Support\Facades\Schema;

class EventsAdminController extends Controller
{
    public function __construct()
    {
        try {
            if (Schema::hasTable('units') && !Schema::hasColumn('units', 'deleted_at')) {
                Schema::table('units', function ($table) {
                    $table->softDeletes();
                });
            }
            if (Schema::hasTable('sub_units') && !Schema::hasColumn('sub_units', 'deleted_at')) {
                Schema::table('sub_units', function ($table) {
                    $table->softDeletes();
                });
            }
            if (Schema::hasTable('gdpr_compliances') && !Schema::hasColumn('gdpr_compliances', 'deleted_at')) {
                Schema::table('gdpr_compliances', function ($table) {
                    $table->softDeletes();
                });
            }
        } catch (\Exception $e) {
            // Ignore
        }
    }

    public function create()
    {
        $gdprOptions = GdprCompliance::where('is_active', true)->orderBy('name', 'asc')->get();

        $unitSpocRoleId = \DB::table('roles')->where('name', 'unit_spoc')->value('id');
        $unitSpocs = \App\Models\User::where('role_id', $unitSpocRoleId)->where('status', 1)->orderBy('name', 'asc')->get();

        return view('admin.event-management', compact('gdprOptions', 'unitSpocs'));
    }

    public function store(Request $request)
    {
        $rules = [
            'event_code' => 'required|string|max:100|unique:events,event_code',
            'name' => 'required|string|max:255',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after_or_equal:start_date',
            'location' => 'required|string|max:255',
            'type' => 'required|string|in:Hospitality,Non-Hospitality',
            'scope' => 'required|string|in:internal,external',
            'nomination_deadline' => 'required|date',
            'nomination_limit' => 'required|integer|min:1',
            'gdpr_compliance' => 'required|string|max:255',
            'unit_spocs' => 'nullable|array',
            'unit_spocs.*' => 'exists:users,id',
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
        $event->scope = $validated['scope'];
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
        $event->created_by = Auth::id();
        $event->last_updated = Auth::id();

        $event->save();

        if ($request->has('unit_spocs')) {
            foreach ($request->input('unit_spocs') as $userId) {
                \DB::table('event_assignments')->insert([
                    'event_id' => $event->id,
                    'user_id' => $userId,
                    'assigned_by' => Auth::id(),
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }
        }

        return redirect()->route('admin-events')->with('success', 'Event stored successfully.');
    }

    public function update(Request $request, $id)
    {
        // Programmatically run migrations in case new columns are not migrated yet
        Artisan::call('migrate', ['--force' => true]);

        $event = Events::findOrFail($id);

        $rules = [
            'event_code' => 'required|string|max:100|unique:events,event_code,' . $id,
            'name' => 'required|string|max:255',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after_or_equal:start_date',
            'location' => 'required|string|max:255',
            'type' => 'required|string|in:Hospitality,Non-Hospitality',
            'scope' => 'required|string|in:internal,external',
            'nomination_deadline' => 'required|date',
            'nomination_limit' => 'required|integer|min:1',
            'gdpr_compliance' => 'required|string|max:255',
            'unit_spocs' => 'nullable|array',
            'unit_spocs.*' => 'exists:users,id',
        ];

        if ($request->input('type') === 'Hospitality') {
            $rules['declaration'] = 'required|string';
            $rules['invite_for'] = 'required|string|max:255';
            $rules['invite_spouser'] = 'required|string|max:255';
            $rules['govt_company'] = 'required|string|max:255';
        }

        $validated = $request->validate($rules);

        $event->event_code = $validated['event_code'];
        $event->name = $validated['name'];
        $event->start_date = $validated['start_date'];
        $event->end_date = $validated['end_date'];
        $event->location = $validated['location'];
        $event->type = $validated['type'] === 'Hospitality' ? 'hospitality' : 'non_hospitality';
        $event->scope = $validated['scope'];
        $event->nomination_deadline = $validated['nomination_deadline'];
        $event->max_nominees_per_form = $validated['nomination_limit'];
        $event->gdpr_compliance = $validated['gdpr_compliance'];

        if ($event->type === 'hospitality') {
            $event->declaration = $validated['declaration'];
            $event->invite_for = $validated['invite_for'];
            $event->invite_spouser = $validated['invite_spouser'];
            $event->govt_company = $validated['govt_company'];
        } else {
            $event->declaration = null;
            $event->invite_for = null;
            $event->invite_spouser = null;
            $event->govt_company = null;
        }

        $event->last_updated = Auth::id();

        $event->save();

        // Sync assigned unit spocs (keep nominators, delete existing unit spocs)
        $unitSpocRoleId = \DB::table('roles')->where('name', 'unit_spoc')->value('id');
        $unitSpocIdsForEvent = \App\Models\User::where('role_id', $unitSpocRoleId)->pluck('id')->toArray();

        \DB::table('event_assignments')
            ->where('event_id', $event->id)
            ->whereIn('user_id', $unitSpocIdsForEvent)
            ->delete();

        if ($request->has('unit_spocs')) {
            foreach ($request->input('unit_spocs') as $userId) {
                \DB::table('event_assignments')->insert([
                    'event_id' => $event->id,
                    'user_id' => $userId,
                    'assigned_by' => Auth::id(),
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }
        }

        return redirect()->route('admin-events')->with('success', 'Event updated successfully.');
    }
}
