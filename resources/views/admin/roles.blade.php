@extends('layouts.app')

@section('content')
<!-- SECTION A: ROLE & ACCESS MANAGEMENT -->
<div id="panel-roles" class="admin-panel bg-white p-4 rounded-4 border">
  <div class="d-flex justify-content-between align-items-center mb-3">
    <div>
      <h2>Role & Access Management</h2>
      <p class="text-secondary fs-09">Configure access permissions, custom roles, and security groups.</p>
    </div>
    <button class="btn btn-primary btn-sm bg-primary" onclick="showAddRoleModal()"><i class="bi bi-plus-lg me-1"></i> Add Role</button>
  </div>

  <!-- Role table -->
  <div class="table-responsive mb-4">
    <table class="table table-hover align-middle" id="roles-table">
      <thead>
        <tr class="table-light">
          <th>Access Group / Role</th>
          <th>Description</th>
          <th>Permissions Configured</th>
          <th>Actions</th>
        </tr>
      </thead>
      <tbody>
        <tr onclick="selectRole('Admin', 'Full system access and master domain boundary administration.')" style="cursor:pointer;" class="table-active">
          <td class="fw-semibold">Admin</td>
          <td>Full system access and master domain boundaries</td>
          <td><span class="badge bg-primary">All Modules</span></td>
          <td>
            <button class="btn btn-sm btn-link text-decoration-none py-0" onclick="event.stopPropagation(); alert('Access restricted: System Admin Role cannot be deleted.')">Delete</button>
          </td>
        </tr>
        <tr onclick="selectRole('Event OPS', 'Manages event queues, imports contacts, and issues invitations.')" style="cursor:pointer;">
          <td class="fw-semibold">Event OPS</td>
          <td>Manages event queues, imports contacts, and issues invitations</td>
          <td><span class="badge bg-secondary">Events, Queue, Contacts</span></td>
          <td>
            <button class="btn btn-sm btn-link text-decoration-none text-danger py-0" onclick="event.stopPropagation(); deleteRow(this)">Delete</button>
          </td>
        </tr>
        <tr onclick="selectRole('Unit SPOC', 'Department and business unit nomination approvals.')" style="cursor:pointer;">
          <td class="fw-semibold">Unit SPOC</td>
          <td>Department and business unit nomination approvals</td>
          <td><span class="badge bg-secondary">Dashboard, Queue</span></td>
          <td>
            <button class="btn btn-sm btn-link text-decoration-none text-danger py-0" onclick="event.stopPropagation(); deleteRow(this)">Delete</button>
          </td>
        </tr>
        <tr onclick="selectRole('Nominator', 'Direct event nomination submission access.')" style="cursor:pointer;">
          <td class="fw-semibold">Nominator</td>
          <td>Direct event nomination submission access</td>
          <td><span class="badge bg-secondary">Dashboard, Events</span></td>
          <td>
            <button class="btn btn-sm btn-link text-decoration-none text-danger py-0" onclick="event.stopPropagation(); deleteRow(this)">Delete</button>
          </td>
        </tr>
      </tbody>
    </table>
  </div>

  <!-- Permissions Configurator Form -->
  <div class="border rounded-4 p-3 bg-light-orange bg-opacity-25 mt-4">
    <h5 class="fw-bold mb-3" id="selected-role-name">Configure Permissions for Admin</h5>
    <div class="row g-3">
      <div class="col-md-6 col-lg-4">
        <div class="form-check form-switch">
          <input class="form-check-input permission-toggle" type="checkbox" id="perm-dashboard" checked />
          <label class="form-check-label fw-semibold" for="perm-dashboard">Access Dashboard</label>
        </div>
      </div>
      <div class="col-md-6 col-lg-4">
        <div class="form-check form-switch">
          <input class="form-check-input permission-toggle" type="checkbox" id="perm-users" checked />
          <label class="form-check-label fw-semibold" for="perm-users">Manage Users</label>
        </div>
      </div>
      <div class="col-md-6 col-lg-4">
        <div class="form-check form-switch">
          <input class="form-check-input permission-toggle" type="checkbox" id="perm-events" checked />
          <label class="form-check-label fw-semibold" for="perm-events">Manage Events</label>
        </div>
      </div>
      <div class="col-md-6 col-lg-4">
        <div class="form-check form-switch">
          <input class="form-check-input permission-toggle" type="checkbox" id="perm-queue" checked />
          <label class="form-check-label fw-semibold" for="perm-queue">Monitor Queues</label>
        </div>
      </div>
      <div class="col-md-6 col-lg-4">
        <div class="form-check form-switch">
          <input class="form-check-input permission-toggle" type="checkbox" id="perm-contacts" checked />
          <label class="form-check-label fw-semibold" for="perm-contacts">Manage Contacts</label>
        </div>
      </div>
      <div class="col-md-6 col-lg-4">
        <div class="form-check form-switch">
          <input class="form-check-input permission-toggle" type="checkbox" id="perm-cms" checked />
          <label class="form-check-label fw-semibold" for="perm-cms">Edit CMS Content</label>
        </div>
      </div>
    </div>
    
    <div class="mt-4 d-flex justify-content-between align-items-center">
      <button class="btn btn-primary btn-sm bg-primary" onclick="savePermissions()">Save Permissions</button>
      <span class="text-secondary fs-08" id="role-tracker-banner">Last updated by Yash Purkar at 2026-06-12 16:20:00</span>
    </div>
  </div>
</div>
@endsection

@push('scripts')
<script>
let currentRole = 'Admin';
function selectRole(roleName, desc) {
    currentRole = roleName;
    document.getElementById('selected-role-name').textContent = `Configure Permissions for ${roleName}`;
    
    // Highlight table row
    const rows = document.querySelectorAll('#roles-table tbody tr');
    rows.forEach(r => {
        if (r.cells[0].textContent.trim() === roleName) {
            r.classList.add('table-active');
        } else {
            r.classList.remove('table-active');
        }
    });

    // Simulate permissions check state based on role
    const toggles = document.querySelectorAll('.permission-toggle');
    toggles.forEach(t => {
        if (roleName === 'Admin') {
            t.checked = true;
        } else if (roleName === 'Event OPS') {
            t.checked = ['perm-dashboard', 'perm-events', 'perm-queue', 'perm-contacts'].includes(t.id);
        } else if (roleName === 'Unit SPOC') {
            t.checked = ['perm-dashboard', 'perm-queue'].includes(t.id);
        } else {
            t.checked = ['perm-dashboard', 'perm-events'].includes(t.id);
        }
    });
}

function savePermissions() {
    const timestamp = new Date().toISOString().slice(0, 19).replace('T', ' ');
    document.getElementById('role-tracker-banner').textContent = `Last updated by Yash Purkar at ${timestamp}`;
    showToast(`Permissions updated for ${currentRole} successfully!`);
}

function showAddRoleModal() {
    const newRole = prompt("Enter the name of the new access role:");
    if (newRole) {
        const tableBody = document.querySelector('#roles-table tbody');
        const tr = document.createElement('tr');
        tr.style.cursor = 'pointer';
        tr.onclick = () => selectRole(newRole, 'Custom role permissions set.');
        tr.innerHTML = `
            <td class="fw-semibold">${newRole}</td>
            <td>Custom application access role</td>
            <td><span class="badge bg-secondary">Custom</span></td>
            <td><button class="btn btn-sm btn-link text-danger py-0" onclick="event.stopPropagation(); deleteRow(this)">Delete</button></td>
        `;
        tableBody.appendChild(tr);
        showToast(`Access role "${newRole}" created successfully.`);
    }
}

function deleteRow(btn) {
    if (confirm("Are you sure you want to delete this row?")) {
        btn.closest('tr').remove();
        showToast("Record deleted successfully.");
    }
}
</script>
@endpush
