<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Events;
use App\Models\Unit;
use App\Models\SubUnit;
use App\Models\GdprCompliance;
use App\Models\NominatorEventLimit;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Schema;
use Carbon\Carbon;

class AdminController extends Controller
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

    public function dashboard()
    {
        return view('admin.dashboard.admin-dashboard');
    }



    public function users()
    {
        $users = \App\Models\User::with('role')
            ->whereHas('role', function ($query) {
                $query->where('name', '!=', 'admin');
            })
            ->orderBy('name', 'asc')
            ->get();

        return view('admin.users', compact('users'));
    }

    public function createUserForm()
    {
        $roles = \App\Models\Role::orderBy('display_name', 'asc')->get();
        return view('admin.create-user', compact('roles'));
    }

    public function storeUser(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'contact_no' => 'nullable|string|max:100',
            'email' => 'required|string|email|max:255|unique:users,email',
            'password' => 'required|string|min:8',
            'role_id' => 'required|exists:roles,id',
            'sso_id' => 'nullable|string|max:255|unique:users,sso_id',
            'status' => 'required|in:0,1',
        ]);

        \App\Models\User::create([
            'name' => $validated['name'],
            'last_name' => $validated['last_name'],
            'contact_no' => $validated['contact_no'] ?: null,
            'email' => $validated['email'],
            'password' => bcrypt($validated['password']),
            'role_id' => $validated['role_id'],
            'sso_id' => $validated['sso_id'] ?: null,
            'status' => (int) $validated['status'],
        ]);

        return redirect()->route('admin-users')->with('success', 'User created successfully.');
    }

    public function toggleUserStatus($id)
    {
        $user = \App\Models\User::findOrFail($id);
        $user->status = $user->status == 1 ? 0 : 1;
        $user->save();

        return response()->json([
            'success' => true,
            'message' => 'User status updated successfully.',
            'status' => $user->status
        ]);
    }

    public function events()
    {
        $events = Events::orderBy('created_at', 'desc')->get()->map(function ($event) {
            // Keep raw values for editing inputs
            $event->raw_start_date = Carbon::parse($event->getRawOriginal('start_date'))->format('Y-m-d');
            $event->raw_end_date = Carbon::parse($event->getRawOriginal('end_date'))->format('Y-m-d');
            $event->raw_nomination_deadline = Carbon::parse($event->getRawOriginal('nomination_deadline'))->format('Y-m-d\TH:i');

            // Formatted values for display
            $event->start_date = Carbon::parse($event->start_date)->format('d-m-Y');
            $event->end_date = Carbon::parse($event->end_date)->format('d-m-Y');
            $event->nomination_deadline = Carbon::parse($event->nomination_deadline)->format('d-m-Y H:i');
            return $event;
        });

        $gdprOptions = GdprCompliance::where('is_active', true)->orderBy('name', 'asc')->get();

        return view('admin.events', compact('events', 'gdprOptions'));
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

    public function dropdown()
    {
        return view('admin.dropdown');
    }

    // --- Units CRUD ---
    public function getUnits()
    {
        return response()->json(Unit::orderBy('name', 'asc')->get());
    }

    public function storeUnit(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:100|unique:units,name',
        ]);

        $unit = Unit::create([
            'name' => $validated['name'],
            'is_active' => true,
            'created_by' => auth()->id(),
        ]);

        return response()->json(['success' => true, 'message' => 'Business Unit created successfully.', 'unit' => $unit]);
    }

    public function updateUnit(Request $request, $id)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:100|unique:units,name,' . $id,
        ]);

        $unit = Unit::findOrFail($id);
        $unit->update([
            'name' => $validated['name'],
            'updated_by' => auth()->id(),
        ]);

        return response()->json(['success' => true, 'message' => 'Business Unit updated successfully.', 'unit' => $unit]);
    }

    public function toggleUnitStatus($id)
    {
        $unit = Unit::findOrFail($id);
        $unit->is_active = !$unit->is_active;
        $unit->updated_by = auth()->id();
        $unit->save();

        return response()->json(['success' => true, 'message' => 'Business Unit status updated.', 'is_active' => $unit->is_active]);
    }

    public function deleteUnit($id)
    {
        $unit = Unit::findOrFail($id);
        $unit->subUnits()->delete();
        $unit->delete();

        return response()->json(['success' => true, 'message' => 'Business Unit and associated sub-units deleted successfully.']);
    }

    // --- Sub Units CRUD ---
    public function getSubUnits()
    {
        $subUnits = SubUnit::with('unit')->orderBy('name', 'asc')->get();
        return response()->json($subUnits);
    }

    public function storeSubUnit(Request $request)
    {
        $validated = $request->validate([
            'unit_id' => 'required|exists:units,id',
            'name' => 'required|string|max:100',
        ]);

        $exists = SubUnit::where('unit_id', $validated['unit_id'])->where('name', $validated['name'])->exists();
        if ($exists) {
            return response()->json(['errors' => ['name' => ['This sub-unit name already exists under the selected business unit.']]], 422);
        }

        $subUnit = SubUnit::create([
            'unit_id' => $validated['unit_id'],
            'name' => $validated['name'],
            'is_active' => true,
            'created_by' => auth()->id(),
        ]);

        return response()->json(['success' => true, 'message' => 'Sub Unit created successfully.', 'sub_unit' => $subUnit->load('unit')]);
    }

    public function updateSubUnit(Request $request, $id)
    {
        $validated = $request->validate([
            'unit_id' => 'required|exists:units,id',
            'name' => 'required|string|max:100',
        ]);

        $exists = SubUnit::where('unit_id', $validated['unit_id'])
            ->where('name', $validated['name'])
            ->where('id', '!=', $id)
            ->exists();
        if ($exists) {
            return response()->json(['errors' => ['name' => ['This sub-unit name already exists under the selected business unit.']]], 422);
        }

        $subUnit = SubUnit::findOrFail($id);
        $subUnit->update([
            'unit_id' => $validated['unit_id'],
            'name' => $validated['name'],
            'updated_by' => auth()->id(),
        ]);

        return response()->json(['success' => true, 'message' => 'Sub Unit updated successfully.', 'sub_unit' => $subUnit->load('unit')]);
    }

    public function toggleSubUnitStatus($id)
    {
        $subUnit = SubUnit::findOrFail($id);
        $subUnit->is_active = !$subUnit->is_active;
        $subUnit->updated_by = auth()->id();
        $subUnit->save();

        return response()->json(['success' => true, 'message' => 'Sub Unit status updated.', 'is_active' => $subUnit->is_active]);
    }

    public function deleteSubUnit($id)
    {
        $subUnit = SubUnit::findOrFail($id);
        $subUnit->delete();

        return response()->json(['success' => true, 'message' => 'Sub Unit deleted successfully.']);
    }

    // --- GDPR CRUD ---
    public function getGdpr()
    {
        return response()->json(GdprCompliance::orderBy('name', 'asc')->get());
    }

    public function storeGdpr(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255|unique:gdpr_compliances,name',
        ]);

        $gdpr = GdprCompliance::create([
            'name' => $validated['name'],
            'is_active' => true,
            'created_by' => auth()->id(),
        ]);

        return response()->json(['success' => true, 'message' => 'GDPR compliance option created successfully.', 'gdpr' => $gdpr]);
    }

    public function updateGdpr(Request $request, $id)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255|unique:gdpr_compliances,name,' . $id,
        ]);

        $gdpr = GdprCompliance::findOrFail($id);
        $gdpr->update([
            'name' => $validated['name'],
            'updated_by' => auth()->id(),
        ]);

        return response()->json(['success' => true, 'message' => 'GDPR compliance option updated successfully.', 'gdpr' => $gdpr]);
    }

    public function toggleGdprStatus($id)
    {
        $gdpr = GdprCompliance::findOrFail($id);
        $gdpr->is_active = !$gdpr->is_active;
        $gdpr->updated_by = auth()->id();
        $gdpr->save();

        return response()->json(['success' => true, 'message' => 'GDPR compliance status updated.', 'is_active' => $gdpr->is_active]);
    }

    public function deleteGdpr($id)
    {
        $gdpr = GdprCompliance::findOrFail($id);
        $gdpr->delete();

        return response()->json(['success' => true, 'message' => 'GDPR compliance option deleted successfully.']);
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

    public function nominatorLimits()
    {
        $limits = NominatorEventLimit::with(['event', 'nominator', 'creator', 'updater'])
            ->orderBy('created_at', 'desc')
            ->get();
        
        $events = Events::where('status', 'ongoing')->orderBy('name', 'asc')->get();
        
        $nominators = \App\Models\User::whereHas('role', function ($query) {
            $query->where('name', 'nominator');
        })->orderBy('name', 'asc')->get();

        return view('admin.nominator-limits', compact('limits', 'events', 'nominators'));
    }

    public function storeNominatorLimit(Request $request)
    {
        $validated = $request->validate([
            'event_id' => 'required|exists:events,id',
            'nominator_id' => 'required|exists:users,id',
            'max_nominees' => 'required|integer|min:0',
        ]);

        $existing = NominatorEventLimit::withTrashed()
            ->where('event_id', $validated['event_id'])
            ->where('nominator_id', $validated['nominator_id'])
            ->first();

        if ($existing) {
            if ($existing->trashed()) {
                $existing->restore();
                $existing->update([
                    'max_nominees' => $validated['max_nominees'],
                    'updated_by' => auth()->id(),
                ]);
                return response()->json([
                    'success' => true,
                    'message' => 'Custom limit restored and updated successfully.'
                ]);
            }
            return response()->json([
                'success' => false,
                'message' => 'A custom limit already exists for this nominator on the selected event.'
            ], 422);
        }

        $limit = NominatorEventLimit::create([
            'event_id' => $validated['event_id'],
            'nominator_id' => $validated['nominator_id'],
            'max_nominees' => $validated['max_nominees'],
            'created_by' => auth()->id(),
            'updated_by' => auth()->id(),
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Custom limit created successfully.',
            'limit' => $limit->load(['event', 'nominator'])
        ]);
    }

    public function updateNominatorLimit(Request $request, $id)
    {
        $validated = $request->validate([
            'max_nominees' => 'required|integer|min:0',
        ]);

        $limit = NominatorEventLimit::findOrFail($id);
        $limit->update([
            'max_nominees' => $validated['max_nominees'],
            'updated_by' => auth()->id(),
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Custom limit updated successfully.',
            'limit' => $limit->load(['event', 'nominator'])
        ]);
    }

    public function deleteNominatorLimit($id)
    {
        $limit = NominatorEventLimit::findOrFail($id);
        $limit->delete();

        return response()->json([
            'success' => true,
            'message' => 'Custom limit deleted successfully.'
        ]);
    }
}
