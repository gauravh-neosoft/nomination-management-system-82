<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Events;
use App\Models\Unit;
use App\Models\SubUnit;
use App\Models\GdprCompliance;
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
}
